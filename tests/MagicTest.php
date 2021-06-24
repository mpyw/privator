<?php

namespace Mpyw\Privator\Tests;

use Mpyw\Privator\ClassProxyInterface;
use Mpyw\Privator\Proxy;
use PHPUnit\Framework\TestCase;

class MagicTest extends TestCase
{
    /**
     * @var MagicClass|ClassProxyInterface
     */
    protected $Magic;

    /**
     * @before
     */
    public function before()
    {
        $this->Magic = Proxy::get(MagicClass::class);
    }

    public function testValidCall()
    {
        $return = $this->Magic::newWithoutConstructor()->magicMethod('arg');
        $this->assertEquals('magicMethod(arg) called', $return);
    }

    public function testInvalidCall()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->Magic::newWithoutConstructor()->undefinedMethod('arg');
    }

    public function testValidCallStatic()
    {
        $return = $this->Magic::staticMagicMethod('arg');
        $this->assertEquals('staticMagicMethod(arg) called', $return);
    }

    public function testInvalidCallStatic()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->Magic::undefinedStaticMethod('arg');
    }

    public function testValidCallDirectly()
    {
        $return = $this->Magic::newWithoutConstructor()->__call('magicMethod', ['arg']);
        $this->assertEquals('magicMethod(arg) called', $return);
    }

    public function testInvalidCallDirectly()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->Magic::newWithoutConstructor()->__call('undefinedMethod', ['arg']);
    }

    public function testValidCallStaticDirectly()
    {
        $return = $this->Magic::__callStatic('staticMagicMethod', ['arg']);
        $this->assertEquals('staticMagicMethod(arg) called', $return);
    }

    public function testInvalidCallStaticDirectly()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->Magic::__callStatic('undefinedMethod', ['arg']);
    }

    public function testGetAndSet()
    {
        $ins = $this->Magic::newWithoutConstructor();
        $this->assertNull($ins->foo);
        $ins->foo = 'bar';
        $this->assertEquals('bar', $ins->foo);
    }
}
