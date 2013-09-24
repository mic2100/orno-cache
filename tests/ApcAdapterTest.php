<?php

namespace OrnoTest;

use Orno\Cache\Adapter\Apc;

class ApcAdapterTest extends \PHPUnit_Framework_Testcase
{
    protected $adapter;

    public function setUp()
    {
        if (! extension_loaded('apc') && ! extension_loaded('apcu')) {
            $this->markTestSkipped('The APC extension is not loaded and therefore cannot be integration tested');
        }

        $this->adapter = new Apc;
    }

    public function tearDown()
    {
        unset($this->adapter);
    }

    public function testGetAndSet()
    {
        $key = microtime();
        $value = $this->randomString();

        $this->adapter->set($key, $value);

        $this->assertSame($value, $this->adapter->get($key));
        $this->assertInstanceOf('Orno\Cache\Adapter\Apc', $this->adapter->delete($key));
    }

    public function testDelete()
    {
        $key = microtime();
        $value = $this->randomString();

        $this->adapter->set($key, $value);

        $this->assertInstanceOf('Orno\Cache\Adapter\Apc', $this->adapter->delete($key));
        $this->assertFalse($this->adapter->get($key));
    }

    public function testIncrement()
    {
        $key = microtime();
        $value = 100;

        $this->adapter->increment($key, 10);

        $newValue = $this->adapter->get($key);

        $this->assertSame(110, $newValue);
        $this->assertInstanceOf('Orno\Cache\Adapter\Apc', $this->adapter->delete($key));
    }

    public function testDecrement()
    {
        $key = microtime();
        $value = 150;

        $this->adapter->decrement($key, 10);

        $newValue = $this->adapter->get($key);

        $this->assertSame(140, $newValue);
        $this->assertInstanceOf('Orno\Cache\Adapter\Apc', $this->adapter->delete($key));
    }

    public function testSetConfig()
    {
        $this->assertInstanceOf('Orno\Cache\Adapter\Apc', $this->adapter->setConfig([]));
    }

    public function randomString($length = 10)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ";
        $size = strlen( $chars );

        $str = '';
        for($i = 0; $i < $length; $i++) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }
}
