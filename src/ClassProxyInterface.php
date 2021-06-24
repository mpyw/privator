<?php

namespace Mpyw\Privator;

interface ClassProxyInterface
{
    /**
     * Call static method of your class.
     *
     * @param  string $name
     * @param  array  $args
     * @return mixed
     */
    public static function __callStatic(string $name, array $args);

    /**
     * Get static property of your class.
     * If you want to call your own "static function getStatic()":
     *   $proxy->__callStatic('getStatic', $args)
     *
     * @param  string $name
     * @return mixed
     */
    public static function getStatic(string $name);

    /**
     * Set static property of your class.
     * If you want to call your own "static function setStatic()":
     *   $proxy->__callStatic('setStatic', $args)
     *
     * @param string $name
     * @param mixed  $value
     */
    public static function setStatic(string $name, $value);

    /**
     * Create anonymous proxy object of your class.
     * If you want to call your own "static function new()":
     *   $proxy->__callStatic('new', $args)
     *
     * @param  mixed ...$args
     * @return mixed|InstanceProxyInterface
     */
    public static function new(...$args);

    /**
     * Create anonymous proxy object of your class without constructor.
     * If you want to call your own "static function newWithoutConstructor()":
     *   $proxy->__callStatic('newWithoutConstructor', $args)
     *
     * @return mixed|InstanceProxyInterface
     */
    public static function newWithoutConstructor();
}
