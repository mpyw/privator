<?php

class Magic
{
    private $____PRIVATES____;

    public function __call(string $name, array $args)
    {
        if ($name === 'magicMethod') {
            return "magicMethod($args[0]) called";
        }
        throw new \BadMethodCallException;
    }

    public static function __callStatic(string $name, array $args)
    {
        if ($name === 'staticMagicMethod') {
            return "staticMagicMethod($args[0]) called";
        }
        throw new \BadMethodCallException;
    }

    public function __get(string $name)
    {
        return $this->____PRIVATES____[$name] ?? null;
    }

    public function __set(string $name, $value)
    {
        $this->____PRIVATES____[$name] = $value;
    }
}
