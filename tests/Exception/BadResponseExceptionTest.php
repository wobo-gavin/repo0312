<?php

namespace /* Replaced /* Replaced Guzzle */ */Http\Tests\Exception;

use /* Replaced /* Replaced Guzzle */ */Http\Exception\BadResponseException;
use /* Replaced /* Replaced Guzzle */ */Http\/* Replaced /* Replaced Psr7 */ */\Request;
use /* Replaced /* Replaced Guzzle */ */Http\/* Replaced /* Replaced Psr7 */ */\Response;
use PHPUnit\Framework\TestCase;

class BadResponseExceptionTest extends TestCase
{
    public function testHasNoResponse()
    {
        $req = new Request('GET', '/');
        $prev = new \Exception();
        $response = new Response();
        $e = new BadResponseException('foo', $req, $response, $prev);
        self::assertSame($req, $e->getRequest());
        self::assertSame($response, $e->getResponse());
        self::assertTrue($e->hasResponse());
        self::assertSame('foo', $e->getMessage());
        self::assertSame($prev, $e->getPrevious());
    }
}
