<?php

require __DIR__ . '/MagicClass.php';

use mpyw\Privator\ProxyException;

/**
 * @requires PHP 7.0
 */
class MagicTest extends \Codeception\TestCase\Test
{
    use mpyw\Privator\Proxy;

    public function _before()
    {
        $this->Magic = self::getProxy(Magic::class);
    }
}
