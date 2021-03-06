<?php

namespace /* Replaced Guzzle */Http\Test\Handler;

use /* Replaced Guzzle */Http\Handler\EasyHandle;
use PHPUnit\Framework\TestCase;

/**
 * @covers \/* Replaced Guzzle */Http\Handler\EasyHandle
 */
class EasyHandleTest extends TestCase
{
    public function testEnsuresHandleExists()
    {
        $easy = new EasyHandle;
        unset($easy->handle);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('The EasyHandle has been released');
        $easy->handle;
    }
}
