<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ConnectException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ConnectException
 */
class ConnectExceptionTest extends TestCase
{
    public function testHasNoResponse()
    {
        $req = new Request('GET', '/');
        $prev = new \Exception();
        $e = new ConnectException('foo', $req, $prev, ['foo' => 'bar']);
        self::assertSame($req, $e->getRequest());
        self::assertNull($e->getResponse());
        self::assertFalse($e->hasResponse());
        self::assertSame('foo', $e->getMessage());
        self::assertSame('bar', $e->getHandlerContext()['foo']);
        self::assertSame($prev, $e->getPrevious());
    }
}
