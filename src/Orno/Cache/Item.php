<?php

namespace Orno\Cache;

use Psr\Cache\ItemInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * Item
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
class Item implements ItemInterface
{
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
     * This is set to true so if the cache item is modified it can be saved
     *
     * @var boolean
     */
    protected $modified = false;

    /**
     * Construct
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
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
