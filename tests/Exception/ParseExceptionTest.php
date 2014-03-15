<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ParseException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ParseException
 */
class ParseExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testHasResponse()
    {
        $res = new Response(200);
        $e = new ParseException('foo', $res);
        $this->assertSame($res, $e->getResponse());
        $this->assertEquals('foo', $e->getMessage());
    }
}
