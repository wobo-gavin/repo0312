<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Parser;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\ParserRegistry;

/**
 * /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\ParserRegistry
 */
class ParserRegistryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testStoresObjects()
    {
        $r = new ParserRegistry();
        $c = new \stdClass();
        $r->registerParser('foo', $c);
        $this->assertSame($c, $r->getParser('foo'));
    }

    public function testReturnsNullWhenNotFound()
    {
        $r = new ParserRegistry();
        $this->assertNull($r->getParser('FOO'));
    }

    public function testReturnsLazyLoadedDefault()
    {
        $r = new ParserRegistry();
        $c = $r->getParser('cookie');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Cookie\CookieParser', $c);
        $this->assertSame($c, $r->getParser('cookie'));
    }
}
