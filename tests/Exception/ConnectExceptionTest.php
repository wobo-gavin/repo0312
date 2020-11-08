<?php

namespace /* Replaced Guzzle */Http\Tests\Exception;

use /* Replaced Guzzle */Http\Exception\ConnectException;
use /* Replaced Guzzle */Http\/* Replaced Psr7 */\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;

/**
 * @covers \/* Replaced Guzzle */Http\Exception\ConnectException
 */
class ConnectExceptionTest extends TestCase
{
    public function testHasRequest()
    {
        $req = new Request('GET', '/');
        $prev = new \Exception();
        $e = new ConnectException('foo', $req, $prev, ['foo' => 'bar']);
        self::assertInstanceOf(NetworkExceptionInterface::class, $e);
        self::assertNotInstanceOf(RequestExceptionInterface::class, $e);
        self::assertSame($req, $e->getRequest());
        self::assertSame('foo', $e->getMessage());
        self::assertSame('bar', $e->getHandlerContext()['foo']);
        self::assertSame($prev, $e->getPrevious());
    }
}
