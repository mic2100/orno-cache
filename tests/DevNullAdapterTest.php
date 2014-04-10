<?php

namespace OrnoTest;

use Orno\Cache\Adapter\DevNullAdapter;

class DevNullAdapterTest extends \PHPUnit_Framework_Testcase
{
    protected $adapter;

    public function setUp()
    {
        $this->adapter = new DevNullAdapter;
    }

    public function tearDown()
    {
        unset($this->adapter);
    }

    public function testGetAndSet()
    {
        $key = $this->randomString(5);
        $value = $this->randomString(20);

        $this->adapter->set($key, $value, 2000);

        $this->assertNull($this->adapter->get($key));
        $this->assertInstanceOf('Orno\Cache\Adapter\DevNullAdapter', $this->adapter->delete($key));
    }

    public function testDelete()
    {
        $key = $this->randomString(5);
        $value = $this->randomString(20);

        $this->adapter->set($key, $value);

        $this->assertInstanceOf('Orno\Cache\Adapter\DevNullAdapter', $this->adapter->delete($key));
        $this->assertNull($this->adapter->get($key));
    }

    public function testIncrement()
    {
        $key = $this->randomString(5);
        $value = 100;

        $this->adapter->set($key, $value);
        $this->adapter->increment($key, 10);

        $this->assertNull($this->adapter->get($key));
        $this->assertInstanceOf('Orno\Cache\Adapter\DevNullAdapter', $this->adapter->delete($key));
    }

    public function testDecrement()
    {
        $key = $this->randomString(5);
        $value = 150;

        $this->adapter->set($key, $value);
        $this->adapter->decrement($key, 10);

        $this->assertNull($this->adapter->get($key));
        $this->assertInstanceOf('Orno\Cache\Adapter\DevNullAdapter', $this->adapter->delete($key));
    }

    public function testSetConfig()
    {
        $this->assertInstanceOf('Orno\Cache\Adapter\DevNullAdapter', $this->adapter->setConfig([]));
    }

    public function randomString($length = 10)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen( $chars );

        $str = '';
        for($i = 0; $i < $length; $i++) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }
}
