<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see LICENSE file)
 */
namespace Orno\Cache\Adapter;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * Etcd:
 * A highly-available key value store for shared configuration and service discovery.
 * Etcd is written in Go and uses the Raft consensus algorithm to manage a highly-available replicated log
 *
 * To download etcd go to:
 * https://github.com/coreos/etcd
 *
 **
 * EtcdAdapter
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
class EtcdAdapter extends AbstractAdapter
{
    /**
     * @var \Guzzle\Http\Client
     */
    protected $client;

    /**
     * The host of the etcd server
     *
     * @var string
     */
    protected $host = 'http://127.0.0.1';

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
        $this->client = new Client(
            $this->generateHost(),
            [],
            ['exceptions' => false]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        try {
            $request = $this->client->get($this->generatePath($key));
            $response = $request->send();
            $array = $response->json();
        } catch(ClientErrorResponseException $e) {
            return false;
        }

        if (empty($array) || ! isset($array['node']['value'])) {
            return false;
        }

        return $array['node']['value'];
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

        $request = $this->client->put(
            $this->generatePath($key),
            null,
            [
                'value' => $data,
                'ttl' => $this->getExpiry(),
            ]
        );
        $request->send();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    public function delete($key)
    {
        $request = $this->client->delete($this->generatePath($key));
        $request->send();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    public function persist($key, $value)
    {
        $request = $this->client->put(
            $this->generatePath($key),
            null,
            ['value' => $data]
        );
        $request->send();

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
        $this->set($key, $data);

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
        $this->set($key, $data);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    public function flush()
    {
        $request = $this->client->delete($this->generatePath('?recursive=true'));
        $request->send();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\EtcdAdapter
     */
    public function setConfig(array $config)
    {
        if (array_key_exists('host', $config)) {
            $this->url = $config['host'];
        }

        if (array_key_exists('expiry', $config)) {
            $this->setDefaultExpiry($config['expiry']);
        }

        return $this;
    }

    /**
     * Generates the etcd path with embeded key
     *
     * @param string $key
     * @return string
     */
    protected function generatePath($key)
    {
        return sprintf("/v2/keys/%s", $key);
    }

    /**
     * Generates the etcd url with embeded key
     *
     * @return string
     */
    protected function generateHost()
    {
        return sprintf("%s:%s", $this->host, $this->port);
    }
}
