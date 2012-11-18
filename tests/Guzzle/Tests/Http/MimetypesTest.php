<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Mimetypes;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Mimetypes
 */
class MimetypesTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testGetsFromExtension()
    {
        $this->assertEquals('text/x-php', Mimetypes::getInstance()->fromExtension('php'));
    }

    public function testGetsFromFilename()
    {
        $this->assertEquals('text/x-php', Mimetypes::getInstance()->fromFilename(__FILE__));
    }

    public function testReturnsNullWhenNoMatchFound()
    {
        $this->assertNull(Mimetypes::getInstance()->fromExtension('foobar'));
    }
}
