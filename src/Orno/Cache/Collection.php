<?php

namespace Orno\Cache;

use Psr\Cache\CollectionInterface;
use Orno\Cache\Item;

/**
 * Collection
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
class Collection implements CollectionInterface
{
    /**
     * Collection of items
     *
     * @var array
     */
    protected $items = [];

    /**
     * Construct
     */
    public function __construct()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        foreach ($this->items as $item) {
            $item->save();
        }
    }

    /**
     * \ArrayAccess
     *
     * @param mixed $offset
     */
    public function offsetExists($offset)
    {

    }

    /**
     * \ArrayAccess
     *
     * @param mixed $offset
     */
    public function offsetGet($offset)
    {

    }

    /**
     * \ArrayAccess
     *
     * @param mixed $offset
     * @param \Orno\Cache\Item $value
     */
    public function offsetSet($offset, Item $value)
    {

    }

    /**
     * \ArrayAccess
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {

    }
}

