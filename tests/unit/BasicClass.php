<?php

class Basic
{
    private static $privateStaticProperty = 'privateStaticPropertyValue';
    private $privateProperty = 'privatePropertyValue';

    private function __construct(string $var) { }

    private static function privateStaticMethod(string $arg)
    {
        return "privateStaticMethod($arg) called";
    }

    private function privateMethod(string $arg)
    {
        return "privateMethod($arg) called";
    }
}
