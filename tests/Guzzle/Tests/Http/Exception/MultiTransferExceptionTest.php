<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\MultiTransferException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\MultiTransferException
 */
class MultiTransferExceptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testHasRequests()
    {
        $r1 = new Request('GET', 'http://www.foo.com');
        $r2 = new Request('GET', 'http://www.foo.com');
        $e = new MultiTransferException();
        $e->addSuccessfulRequest($r1);
        $e->addFailedRequest($r2);
        $this->assertEquals(array($r1), $e->getSuccessfulRequests());
        $this->assertEquals(array($r2), $e->getSuccessfulRequests());
        $this->assertEquals(array($r1, $r2), $e->getAllRequests());
        $this->assertTrue($e->containsRequest($r1));
        $this->assertTrue($e->containsRequest($r2));
        $this->assertFalse($e->containsRequest(new Request('POST', '/foo')));
    }

    public function testCanSetRequests()
    {
        $s = array($r1 = new Request('GET', 'http://www.foo.com'));
        $f = array($r2 = new Request('GET', 'http://www.foo.com'));
        $e = new MultiTransferException();
        $e->setSuccessfulRequests($s);
        $e->setFailedRequests($f);
        $this->assertEquals(array($r1), $e->getSuccessfulRequests());
        $this->assertEquals(array($r2), $e->getSuccessfulRequests());
    }

    public function testAssociatesExceptionsWithRequests()
    {
        $r1 = new Request('GET', 'http://www.foo.com');
        $re1 = new \Exception('foo');
        $re2 = new \Exception('bar');
        $e = new MultiTransferException();
        $e->add($re2);
        $e->addFailedRequestWithException($r1, $re1);
        $this->assertSame($re1, $e->getExceptionForFailedRequest($r1));
        $this->assertNull($e->getExceptionForFailedRequest(new Request('POST', '/foo')));
    }
}