<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Parser\Cookie;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Cookie\CookieParser;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Cookie\CookieParser
 */
class CookieParserTest extends CookieParserProvider
{
    protected $cookieParserClass = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Cookie\CookieParser';

    public function testUrlDecodesCookies()
    {
        $parser = new CookieParser();
        $result = $parser->parseCookie('foo=baz+bar', null, null, true);
        $this->assertEquals(array(
            'foo' => 'baz bar'
        ), $result['cookies']);
    }
}
