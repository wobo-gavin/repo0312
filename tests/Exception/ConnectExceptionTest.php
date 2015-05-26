<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ConnectException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ConnectException
 */
class ConnectExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testHasNoResponse()
    {
        $req = new Request('GET', '/');
        $prev = new \Exception();
        $e = new ConnectException('foo', $req, $prev, ['foo' => 'bar']);
        $this->assertSame($req, $e->getRequest());
        $this->assertNull($e->getResponse());
        $this->assertFalse($e->hasResponse());
        $this->assertEquals('foo', $e->getMessage());
        $this->assertEquals('bar', $e->getHandlerContext()['foo']);
        $this->assertSame($prev, $e->getPrevious());
    }
}
