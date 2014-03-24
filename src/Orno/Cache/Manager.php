<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see LICENSE file)
 */
namespace Orno\Cache;

use Psr\Cache\PoolInterface;
use Orno\Cache\Item;
use Orno\Cache\Collection;
use Orno\Cache\Exception\InvalidArgumentException;

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
     * @return \Psr\Cache\ItemInterface
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getItem($key)
    {
        if (! is_string($key)) {
            throw new InvalidArgumentException("Invalid item key");
        }
        return new Item($key, $this->adapter);
    }

    /**
     * {@inheritdoc}
     *
     * @param array $keys
     * @return \Psr\Cache\Collection
     */
    public function getItems(array $keys = array())
    {
        $collection = new Collection;
        foreach ($keys as $key) {
            $collection[$key] = $this->getItem($key);
        }
        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->adapter->flush();
    }

    /**
     * {@inheritdoc}
     *
     * @param array $keys
     */
    public function deleteItems(array $keys)
    {
        foreach ($keys as $key) {
            $this->getItem($key)->delete();
        }
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
