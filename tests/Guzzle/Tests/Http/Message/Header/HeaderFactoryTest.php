<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message\Header;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Header\HeaderFactory;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Header\HeaderFactory
 */
class HeaderFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testCreatesBasicHeaders()
    {
        $f = new HeaderFactory();
        $h = $f->createHeader('Foo', 'Bar');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Header', $h);
        $this->assertEquals('Foo', $h->getName());
        $this->assertEquals('Bar', (string) $h);
    }

    public function testCreatesSpecificHeaders()
    {
        $f = new HeaderFactory();
        $h = $f->createHeader('Link', '<http>; rel="test"');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Header\Link', $h);
        $this->assertEquals('Link', $h->getName());
        $this->assertEquals('<http>; rel="test"', (string) $h);
    }
}
