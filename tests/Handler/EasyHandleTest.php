<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Test\Handler;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\EasyHandle;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */;
use PHPUnit\Framework\TestCase;

/**
 * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\EasyHandle
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
