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
}
