<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see LICENSE file)
 */
namespace Orno\Cache;

use Psr\Cache\PoolInterface;
use Psr\Cache\InvalidArgumentException;
use Orno\Cache\Item;

/**
 * Manager
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
class Manager implements PoolInterface
{
    /**
     * @var \Orno\Cache\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * Constructor
     *
     * @param \Orno\Cache\Adapter\AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->setAdapter($adapter);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $key
     */
    public function getItem($key)
    {
        return new Item($key);
    }

    /**
     * {@inheritdoc}
     *
     * @param array $keys
     */
    public function getItems(array $keys = array())
    {
        foreach ($keys as $key) {

        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {

    }

    /**
     * {@inheritdoc}
     *
     * @param array $keys
     */
    public function deleteItems(array $keys)
    {

    }

    /**
     * Gets the adapter
     *
     * @return \Orno\Cache\Adapter\CacheAdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Sets the adapter
     *
     * @param  \Orno\Cache\Adapter\AdapterInterface $adapter
     * @return \Orno\Cache\Cache
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }
}
