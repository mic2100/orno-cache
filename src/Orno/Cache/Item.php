<?php

namespace Orno\Cache;

use Psr\Cache\ItemInterface;
use Orno\Cache\Adapter\AdapterInterface;

/**
 * Item
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
class Item implements ItemInterface
{
    /**
     * The adapter that will be used to cache the data
     *
     * @var Orno\Cache\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * The key that will be used to save the data to the adapter
     *
     * @var string
     */
    protected $key;

    /**
     * The value that will be saved to the adapter
     *
     * @var mixed
     */
    protected $value = null;

    /**
     * Stores the time to live in seconds or a value of null for the adapter default
     *
     * @var null|int
     */
    protected $ttl = null;

    /**
     * This is set to true so if the cache item is modified it can be saved
     *
     * @var boolean
     */
    protected $modified = false;

    /**
     * Construct
     *
     * @param string $key
     * @param \Orno\Cache\Adapter\AdapterInterface $adapter
     */
    public function __construct($key, AdapterInterface $adapter)
    {
        $this->key = $key;
        $this->adapter = $adapter;

        $this->value = $this->adapter->get($this->key);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $value
     * @param int|\DateTime $ttl
     */
    public function set($value, $ttl = null)
    {


        $this->modified = true;

        return;
    }

    /**
     * {@inheritdoc}
     *
     * @param type $value
     * @param int|\DateTime $ttl
     */
    public function save($value = null, $ttl = null)
    {

        $this->modified = false;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHit()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function exists()
    {

    }
}
