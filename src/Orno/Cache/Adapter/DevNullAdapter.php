<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see LICENSE file)
 */
namespace Orno\Cache\Adapter;

/**
 * DevNullAdapter
 *
 * This is for use in environments where caching is not needed,
 * it will NOT store the data anywhere and the get() will only return null
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
class DevNullAdapter extends AbstractAdapter
{
    /**
     * {@inheritdoc}
     *
     * @return null
     */
    public function get($key)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function set($key, $data, $expiry = null)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function delete($key)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function persist($key, $value)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function increment($key, $offset = 1)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function decrement($key, $offset = 1)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Orno\Cache\Adapter\Apc
     */
    public function flush()
    {
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
