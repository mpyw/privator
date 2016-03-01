<?php

namespace mpyw\Privator;

use mpyw\Privator\ProxyException;

class Proxy
{
    public static function get(string $classname)
    {
        return new class($classname)
        {
            private static $rc;

            public function __construct(string $classname)
            {
                self::$rc = new \ReflectionClass($classname);
            }

            public static function __callStatic(string $name, array $args) {
                $rc = self::$rc;
                if (method_exists($rc->name, $name)) {
                    $rm = $rc->getMethod($name);
                    if (!$rm->isStatic()) {
                        throw new ProxyException(
                            "Non-static method called statically: " .
                            "$rc->name::$name()");
                    }
                    $rm->setAccessible(true);
                    return $rm->invokeArgs(null, $args);
                }
                if (method_exists($rc->name, '__callStatic')) {
                    return $rc->name::$name(...$args);
                }
                throw new ProxyException(
                    "Call to undefined method: $rc->name::$name()");
            }

            private static function getStaticReflectionProperty(string $name)
            {
                $rc = self::$rc;
                if (property_exists($rc->name, $name)) {
                    $rp = $rc->getProperty($name);
                    if (!$rp->isStatic()) {
                        throw new ProxyException(
                            "Access to undeclared static property: " .
                            "$rc->name::\$$name");
                    }
                    $rp->setAccessible(true);
                    return $rp;
                }
                throw new ProxyException(
                    "Access to undeclared static property: " .
                    "$rc->name::\$$name");
            }

            public static function getStatic(string $name)
            {
                return self::getStaticReflectionProperty($name)->getValue();
            }

            public static function setStatic(string $name, $value)
            {
                self::getStaticReflectionProperty($name)->setValue($name, $value);
            }

            public static function new(array $args = null)
            {
                return new class(self::$rc, $args)
                {
                    private $ro;
                    private $ins;

                    public function __construct(\ReflectionClass $rc, array $args = null)
                    {
                        $this->ins = $rc->newInstanceWithoutConstructor();
                        if ($args !== null && $con = $rc->getConstructor()) {
                            $con->setAccessible(true);
                            $con->invokeArgs($this->ins, $args);
                        }
                        $this->ro = new \ReflectionObject($this->ins);
                    }

                    public function __call(string $name, array $args)
                    {
                        if (method_exists($this->ro->name, $name)) {
                            $rm = $this->ro->getMethod($name);
                            $rm->setAccessible(true);
                            return $rm->invokeArgs($this->ins, $args);
                        }
                        if (method_exists($this->ro->name, '__call')) {
                            return $this->ins->$name(...$args);
                        }
                        throw new ProxyException(
                            "Call to undefined method: " .
                            "{$this->ro->name}::$name()");
                    }

                    private function getReflectionProperty(string $name)
                    {
                        if (property_exists($this->ins, $name)) {
                            $rp = $this->ro->getProperty($name);
                            $rp->setAccessible(true);
                            return $rp;
                        }
                        throw new ProxyException(
                            "Undefined property: {$this->ro->name}::\$$name");
                    }

                    public function __get(string $name)
                    {
                        try {
                            return $this->getReflectionProperty($name)
                                        ->getValue($this->ins);
                        } catch (ProxyException $e) {
                            try {
                                return $this->__call('__get', [$name]);
                            } catch (ProxyException $_) {
                                throw $e;
                            }
                        }
                    }

                    public function __set(string $name, $value)
                    {
                        try {
                            $property = $this->getReflectionProperty($name);
                            $property->setValue($this->ins, $value);
                        } catch (ProxyException $e) {
                            try {
                                $this->__call('__set', [$name, $value]);
                                return;
                            } catch (ProxyException $_) { } // If __set() is undefined,
                                                            // fallback to the actual property.
                            if (isset($property)) {
                                throw $e; // Static property exists,
                                          // so you cannot create a new field.
                            } else {
                                $this->ins->$name = $value; // Property does not exists
                                                            // so you can create a new field.
                            }
                        }
                    }
                };
            }
        };
    }
}
