<?php

namespace Mpyw\Privator\Tests;

use Mpyw\Privator\ClassProxyInterface;
use Mpyw\Privator\Proxy;
use Mpyw\Privator\ProxyException;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    /**
     * @var BasicClass|ClassProxyInterface
     */
    protected $Basic;

    /**
     * @before
     */
    public function before()
    {
        $this->Basic = Proxy::get(BasicClass::class);
    }

    public function testNewWithoutConstructor()
    {
        $basic = $this->Basic::newWithoutConstructor();
        $this->assertEquals('privatePropertyValue', $basic->privateProperty);
    }

    public function testNewWithInvalidArguments()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(
            \version_compare(PHP_VERSION, '8', '>=')
                ? 'Mpyw\Privator\Tests\BasicClass::__construct(): Argument #1 ($var) must be of type string, null given'
                : 'Argument 1 passed to Mpyw\Privator\Tests\BasicClass::__construct() must be of the type string, null given'
        );

        $basic = $this->Basic::new(null);
        $this->assertEquals('privatePropertyValue', $basic->privateProperty);
    }

    public function testGetStaticPropertyStatically()
    {
        $value = $this->Basic::getStatic('privateStaticProperty');
        $this->assertEquals('privateStaticPropertyValue', $value);
    }

    public function testGetStaticProperty()
    {
        $value = $this->Basic::newWithoutConstructor()->privateStaticProperty;
        $this->assertEquals('privateStaticPropertyValue', $value);
    }

    public function testGetInstancePropertyStatically()
    {
        $this->expectException(ProxyException::class);
        $this->expectExceptionMessage('Access to undeclared static property: Mpyw\Privator\Tests\BasicClass::$privateProperty');

        $value = $this->Basic::getStatic('privateProperty');
        $this->assertEquals('privatePropertyValue', $value);
    }

    public function testGetInstanceProperty()
    {
        $value = $this->Basic::newWithoutConstructor()->privateProperty;
        $this->assertEquals('privatePropertyValue', $value);
    }

    public function testGetUndefinedPropertyStatically()
    {
        $this->expectException(ProxyException::class);
        $this->expectExceptionMessage('Access to undeclared static property: Mpyw\Privator\Tests\BasicClass::$undefined');

        $this->Basic::getStatic('undefined');
    }

    public function testGetUndefinedProperty()
    {
        $this->expectException(ProxyException::class);
        $this->expectExceptionMessage('Undefined property: Mpyw\Privator\Tests\BasicClass::$undefined');

        $this->Basic::newWithoutConstructor()->undefined;
    }

    public function testSetStaticPropertyStatically()
    {
        $this->Basic::setStatic('privateStaticProperty', 'newValue');
        $value = $this->Basic::getStatic('privateStaticProperty');
        $this->assertEquals('newValue', $value);
    }

    public function testSetStaticProperty()
    {
        $ins = $this->Basic::newWithoutConstructor();
        $ins->privateStaticProperty = 'newValue';
        $this->assertEquals('newValue', $ins->privateStaticProperty);
    }

    public function testSetInstancePropertyStatically()
    {
        $this->expectException(ProxyException::class);
        $this->expectExceptionMessage('Access to undeclared static property: Mpyw\Privator\Tests\BasicClass::$privateProperty');

        $this->Basic::setStatic('privateProperty', 'newValue');
        $value = $this->Basic::getStatic('privateProperty');
        $this->assertEquals('newValue', $value);
    }

    public function testSetInstanceProperty()
    {
        $ins = $this->Basic::newWithoutConstructor();
        $ins->privateProperty = 'newValue';
        $this->assertEquals('newValue', $ins->privateProperty);
    }

    public function testSetUndefinedPropertyStatically()
    {
        $this->expectException(ProxyException::class);
        $this->expectExceptionMessage('Access to undeclared static property: Mpyw\Privator\Tests\BasicClass::$undefined');

        $this->Basic::setStatic('undefined', 'newValue');
    }

    public function testSetUndefinedProperty()
    {
        $ins = $this->Basic::newWithoutConstructor();
        $ins->undefined = 'newValue';
        $this->assertEquals('newValue', $ins->undefined);
        (function ($tester) {
            $rp = new \ReflectionProperty($this->ins, 'undefined');
            $tester->assertFalse($rp->isDefault());
        })->call($ins, $this);
    }

    public function testStaticMethodStatically()
    {
        $return = $this->Basic::privateStaticMethod("test");
        $this->assertEquals("privateStaticMethod(test) called", $return);
    }

    public function testStaticMethod()
    {
        $return = $this->Basic::newWithoutConstructor()->privateStaticMethod("test");
        $this->assertEquals("privateStaticMethod(test) called", $return);
    }

    public function testInstanceMethodStatically()
    {
        $this->expectException(ProxyException::class);
        $this->expectExceptionMessage('Non-static method called statically: Mpyw\Privator\Tests\BasicClass::privateMethod()');

        $this->Basic::privateMethod("test");
    }

    public function testInstanceMethod()
    {
        $return = $this->Basic::newWithoutConstructor()->privateMethod("test");
        $this->assertEquals("privateMethod(test) called", $return);
    }
}
