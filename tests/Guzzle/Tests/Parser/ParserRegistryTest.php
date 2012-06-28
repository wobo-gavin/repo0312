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
        $c = new \stdClass();
        ParserRegistry::set('foo', $c);
        $this->assertSame($c, ParserRegistry::get('foo'));
    }

    public function testReturnsNullWhenNotFound()
    {
        $this->assertNull(ParserRegistry::get('FOO'));
    }

    public function testReturnsLazyLoadedDefault()
    {
        // Clear out what might be cached
        $refObject = new \ReflectionClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\ParserRegistry');
        $refProperty = $refObject->getProperty('instances');
        $refProperty->setAccessible(true);
        $refProperty->setValue(null, array());

        $c = ParserRegistry::get('cookie');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Cookie\CookieParser', $c);
        $this->assertSame($c, ParserRegistry::get('cookie'));
    }
}
