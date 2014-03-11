<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see LICENSE file)
 */
namespace Orno\Cache\Adapter;

/**
 * EtcdAdapter
 *
 * Etcd:
 * A highly-available key value store for shared configuration and service discovery.
 * Etcd is written in Go and uses the Raft consensus algorithm to manage a highly-available replicated log
 *
 * https://github.com/coreos/etcd
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
class EtcdAdapter extends AbstractAdapter
{
    /**
     * @var Curl
     */
    protected $curl;

    /**
     * The IP/URL of the etcd server
     *
     * @var string
     */
    protected $url = 'http://127.0.0.1';

    /**
     * The port number of the etcd server
     *
     * @var int
     */
    protected $port = 4001;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->setConfig($config);

        $this->initCurl();
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $this->initCurl();
        $this->setoptCurl(CURLOPT_URL, $this->generateUrl($key))
             ->setoptCurl(CURLOPT_HTTPGET, true)
             ->setoptCurl(CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($this->curl));
        $this->closeCurl();

        if (is_null($response) || ! isset($response->node)) {
            return false;
        }

        return $response->node->value;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    public function set($key, $data, $expiry = null)
    {
        if (is_null($expiry)) {
            $expiry = $this->getExpiry();
        }

        if (is_string($expiry)) {
            $expiry = $this->convertExpiryString($expiry);
        }

        $put = "value={$data}&ttl={$this->getExpiry()}";

        $this->initCurl();
        $this->setoptCurl(CURLOPT_URL, $this->generateUrl($key))
             ->setoptCurl(CURLOPT_CUSTOMREQUEST, "PUT")
             ->setoptCurl(CURLOPT_POSTFIELDS, $put)
             ->setoptCurl(CURLOPT_RETURNTRANSFER, false);
        curl_exec($this->curl);
        $this->closeCurl();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    public function delete($key)
    {
        $this->initCurl();
        $this->setoptCurl(CURLOPT_URL, $this->generateUrl($key))
             ->setoptCurl(CURLOPT_CUSTOMREQUEST, "DELETE")
             ->setoptCurl(CURLOPT_RETURNTRANSFER, false);
        curl_exec($this->curl);
        $this->closeCurl();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    public function persist($key, $value)
    {
        $this->initCurl();
        $this->setoptCurl(CURLOPT_URL, $this->generateUrl($key))
             ->setoptCurl(CURLOPT_CUSTOMREQUEST, "PUT")
             ->setoptCurl(CURLOPT_POSTFIELDS, ['value' => $value])
             ->setoptCurl(CURLOPT_RETURNTRANSFER, false);
        curl_exec($this->curl);
        $this->closeCurl();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    public function increment($key, $offset = 1)
    {
        $data = (int) $this->get($key) + $offset;

        $put = "value={$data}&ttl={$this->getExpiry()}";

        $this->initCurl();
        $this->setoptCurl(CURLOPT_URL, $this->generateUrl($key))
             ->setoptCurl(CURLOPT_CUSTOMREQUEST, "PUT")
             ->setoptCurl(CURLOPT_POSTFIELDS, $put)
             ->setoptCurl(CURLOPT_RETURNTRANSFER, false);
        curl_exec($this->curl);
        $this->closeCurl();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    public function decrement($key, $offset = 1)
    {
        $data = $this->get($key) - $offset;

        $put = "value={$data}&ttl={$this->getExpiry()}";

        $this->initCurl();
        $this->setoptCurl(CURLOPT_URL, $this->generateUrl($key))
             ->setoptCurl(CURLOPT_CUSTOMREQUEST, "PUT")
             ->setoptCurl(CURLOPT_POSTFIELDS, $put)
             ->setoptCurl(CURLOPT_RETURNTRANSFER, false);
        curl_exec($this->curl);
        $this->closeCurl();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    public function flush()
    {
        $this->initCurl();
        $this->setoptCurl(CURLOPT_URL, $this->generateUrl('?recursive=true'))
             ->setoptCurl(CURLOPT_CUSTOMREQUEST, "DELETE")
             ->setoptCurl(CURLOPT_RETURNTRANSFER, false);
        curl_exec($this->curl);
        $this->closeCurl();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    public function setConfig(array $config)
    {
        if (array_key_exists('url', $config)) {
            $this->url = $config['url'];
        }

        if (array_key_exists('expiry', $config)) {
            $this->setDefaultExpiry($config['expiry']);
        }

        return $this;
    }

    /**
     * Initialize the curl library
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    protected function initCurl()
    {
        $this->curl = curl_init();

        return $this;
    }

    /**
     * Runs curl_setopt
     *
     * @param int $option
     * @param string $value
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    protected function setoptCurl($option, $value)
    {
        curl_setopt($this->curl, $option, $value);

        return $this;
    }

    /**
     * Close the curl connection
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    protected function closeCurl()
    {
        curl_close($this->curl);

        return $this;
    }

    /**
     * Generates the etcd url with embeded key
     *
     * @param string $key
     * @return string
     */
    protected function generateUrl($key)
    {
        return sprintf("%s:%s/v2/keys/%s", $this->url, $this->port, $key);
    }
}
