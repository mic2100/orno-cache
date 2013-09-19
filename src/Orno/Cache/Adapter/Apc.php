<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see LICENSE file)
 */
namespace Orno\Cache;

use Orno\Cache\Exception\AdapaterNotAvailableException;

/**
 * Apc
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
class Apc extends AbstractAdapter
{
    /**
     * If this is set to true it will use the APCu extension else it will use the APC (if loaded)
     *
     * @var bool
     */
    protected $apcu = false;

    /**
     * Constructor
     *
     * @throws AdapaterNotAvailableException
     */
    public function __construct()
    {
        if (extension_loaded('apcu')) {
            $this->apcu = true;
        } elseif (! extension_loaded('apc')) {
            throw new AdapaterNotAvailableException('The APC extension is not loaded');
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function get($key)
    {
        if ($this->apcu) {
            return apcu_fetch($key);
        }

        return apc_fetch($key);
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function set($key, $data, $expiry = null)
    {
        if (is_null($expiry)) {
            $expiry = $this->getExpiry();
        }

        if (is_string($expiry)) {
            $expiry = $this->convertExpiryString($expiry);
        }

        if ($this->apcu) {
            apcu_add($key, $data, $expiry);
        } else {
            apc_add($key, $data, $expiry);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function delete($key)
    {
        if ($this->apcu) {
            apcu_delete($key);
        } else {
            apc_delete($key);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function persist($key, $value)
    {
        $this->set($key, $value, 0);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function increment($key, $offset = 1)
    {
        $value = (int) $this->get($key) + $offset;

        $this->set($key, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function decrement($key, $offset = 1)
    {
        $value = (int) $this->get($key) - $offset;

        $this->set($key, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function flush()
    {
        if ($this->apcu) {
            apcu_clear_cache('user');
        } else {
            apc_clear_cache('user');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function setConfig(array $config)
    {
        return $this;
    }
}