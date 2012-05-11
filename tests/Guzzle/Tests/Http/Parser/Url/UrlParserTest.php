<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Parser\Url;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Parser\Url\UrlParser;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Parser\Url\UrlParser
 */
class UrlParserTest extends UrlParserProvider
{
    /**
     * @dataProvider urlProvider
     */
    public function testBuildsUrlsFromParts($url, $parts)
    {
        $this->assertEquals($url, Url::buildUrl($parts));
    }

    public function testCanUseUtf8Query()
    {
        $url = Url::factory('http://www.example.com?µ=a');
        $this->assertEquals('a', $url->getQuery()->get('µ'));
    }

    public function testParsesUtf8UrlQueryStringsWithFragment()
    {
        $parser = new UrlParser();
        $parser->setUtf8Support(true);

        $parts = $parser->parseUrl('http://www.example.com?ሴ=a#fragmentishere');
        $this->assertEquals('ሴ=a', $parts['query']);
        $this->assertEquals('fragmentishere', $parts['fragment']);

        $parts = $parser->parseUrl('http://www.example.com?ሴ=a');
        $this->assertEquals('ሴ=a', $parts['query']);
        $this->assertEquals('', $parts['fragment']);
    }
}
