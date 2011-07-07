<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Curl;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlException;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class CurlExceptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlException
     */
    public function testStoresCurlError()
    {
        $e = new CurlException();
        $this->assertNull($e->getCurlError());
        $this->assertSame($e, $e->setCurlError('test'));
        $this->assertEquals('test', $e->getCurlError());
    }
}