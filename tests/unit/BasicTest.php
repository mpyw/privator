<?php

require __DIR__ . '/BasicClass.php';

use mpyw\Privator\ProxyException;

/**
 * @requires PHP 7.0
 */
class BasicTest extends \Codeception\TestCase\Test
{
    use mpyw\Privator\Proxy;

    public function _before()
    {
        $this->Basic = self::getProxy(Basic::class);
    }

    public function testNewWithoutConstructor()
    {
        $basic = $this->Basic::new();
        $this->assertEquals('privatePropertyValue', $basic->privateProperty);
    }

    /**
     * @expectedException        TypeError
     * @expectedExceptionMessage Argument 1 passed to Basic::__construct()
     *                           must be of the type string, none given
     */
    public function testNewWithInvalidArguments()
    {
        $basic = $this->Basic::new([]);
        $this->assertEquals('privatePropertyValue', $basic->privateProperty);
    }

    public function testGetStaticPropertyStatically()
    {
        $value = $this->Basic::getStatic('privateStaticProperty');
        $this->assertEquals('privateStaticPropertyValue', $value);
    }

    public function testGetStaticProperty()
    {
        $value = $this->Basic->new()->privateStaticProperty;
        $this->assertEquals('privateStaticPropertyValue', $value);
    }

    /**
     * @expectedException        mpyw\Privator\ProxyException
     * @expectedExceptionMessage Access to undeclared static property:
     *                           Basic::$privateProperty
     */
    public function testGetInstancePropertyStatically()
    {
        $value = $this->Basic::getStatic('privateProperty');
        $this->assertEquals('privatePropertyValue', $value);
    }

    public function testGetInstanceProperty()
    {
        $value = $this->Basic->new()->privateProperty;
        $this->assertEquals('privatePropertyValue', $value);
    }

    /**
     * @expectedException        mpyw\Privator\ProxyException
     * @expectedExceptionMessage Access to undeclared static property:
     *                           Basic::$undefined
     */
    public function testGetUndefinedPropertyStatically()
    {
        $this->Basic::getStatic('undefined');
    }

    /**
     * @expectedException        mpyw\Privator\ProxyException
     * @expectedExceptionMessage Undefined property: Basic::$undefined
     */
    public function testGetUndefinedProperty()
    {
        $this->Basic->new()->undefined;
    }

    public function testSetStaticPropertyStatically()
    {
        $this->Basic::setStatic('privateStaticProperty', 'newValue');
        $value = $this->Basic::getStatic('privateStaticProperty');
        $this->assertEquals('newValue', $value);
    }

    public function testSetStaticProperty()
    {
        $ins = $this->Basic->new();
        $ins->privateStaticProperty = 'newValue';
        $this->assertEquals('newValue', $ins->privateStaticProperty);
    }

    /**
     * @expectedException        mpyw\Privator\ProxyException
     * @expectedExceptionMessage Access to undeclared static property:
     *                           Basic::$privateProperty
     */
    public function testSetInstancePropertyStatically()
    {
        $this->Basic::setStatic('privateProperty', 'newValue');
        $value = $this->Basic::getStatic('privateProperty');
        $this->assertEquals('newValue', $value);
    }

    public function testSetInstanceProperty()
    {
        $ins = $this->Basic->new();
        $ins->privateProperty = 'newValue';
        $this->assertEquals('newValue', $ins->privateProperty);
    }

    /**
     * @expectedException        mpyw\Privator\ProxyException
     * @expectedExceptionMessage Access to undeclared static property:
     *                           Basic::$undefined
     */
    public function testSetUndefinedPropertyStatically()
    {
        $this->Basic::setStatic('undefined', 'newValue');
    }

    public function testSetUndefinedProperty()
    {
        $ins = $this->Basic->new();
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
        $return = $this->Basic->new()->privateStaticMethod("test");
        $this->assertEquals("privateStaticMethod(test) called", $return);
    }

    /**
     * @expectedException        mpyw\Privator\ProxyException
     * @expectedExceptionMessage Non-static method called statically:
     *                           Basic::privateMethod()
     */
    public function testInstanceMethodStatically()
    {
        $this->Basic::privateMethod("test");
    }

    public function testInstanceMethod()
    {
        $return = $this->Basic->new()->privateMethod("test");
        $this->assertEquals("privateMethod(test) called", $return);
    }
}
