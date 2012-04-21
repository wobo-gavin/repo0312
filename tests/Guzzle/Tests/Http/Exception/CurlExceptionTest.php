<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\CurlException;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\CurlException
 */
class CurlExceptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testStoresCurlError()
    {
        $e = new CurlException();
        $this->assertNull($e->getError());
        $this->assertNull($e->getErrorNo());
        $this->assertSame($e, $e->setError('test', 12));
        $this->assertEquals('test', $e->getError());
        $this->assertEquals(12, $e->getErrorNo());
    }
}
