<?php

require __DIR__ . '/MagicClass.php';

use mpyw\Privator\Proxy;
use mpyw\Privator\ProxyException;

/**
 * @requires PHP 7.0
 */
class MagicTest extends \Codeception\TestCase\Test
{
    public function _before()
    {
        $this->Magic = Proxy::get(Magic::class);
    }

    public function testValidCall()
    {
        $return = $this->Magic::newWithoutConstructor()->magicMethod('arg');
        $this->assertEquals('magicMethod(arg) called', $return);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testInvalidCall()
    {
        $this->Magic::newWithoutConstructor()->undefinedMethod('arg');
    }

    public function testValidCallStatic()
    {
        $return = $this->Magic::staticMagicMethod('arg');
        $this->assertEquals('staticMagicMethod(arg) called', $return);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testInvalidCallStatic()
    {
        $return = $this->Magic::undefinedStaticMethod('arg');
    }

    public function testGetAndSet()
    {
        $ins = $this->Magic::newWithoutConstructor();
        $this->assertNull($ins->foo);
        $ins->foo = 'bar';
        $this->assertEquals('bar', $ins->foo);
    }
}
