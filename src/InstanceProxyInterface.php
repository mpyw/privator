<?php

namespace Mpyw\Privator;

interface InstanceProxyInterface
{
    /**
     * Call instance method of your class.
     *
     * @param  string $name
     * @param  array  $args
     * @return mixed
     */
    public function __call(string $name, array $args);

    /**
     * Get property of your object.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get(string $name);

    /**
     * Set property of your object.
     *
     * @param  string $name
     * @param  mixed  $value
     */
    public function __set(string $name, $value);
}
