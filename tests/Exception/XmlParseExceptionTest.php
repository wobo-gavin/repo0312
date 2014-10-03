<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\tests\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\XmlParseException;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\XmlParseException
 */
class XmlParseExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testHasError()
    {
        $error = new \LibXMLError();
        $e = new XmlParseException('foo', null, null, $error);
        $this->assertSame($error, $e->getError());
        $this->assertEquals('foo', $e->getMessage());
    }
}
