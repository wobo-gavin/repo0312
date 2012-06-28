<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Parser\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Message\PeclHttpMessageParser;

class PeclHttpMessageParserTest extends MessageParserProvider
{
    protected function setUp()
    {
        if (!function_exists('http_parse_message')) {
            $this->markTestSkipped('pecl_http is not available.');
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Message\PeclHttpMessageParser::parseRequest
     * @dataProvider requestProvider
     */
    public function testParsesRequests($message, $parts)
    {
        $parser = new PeclHttpMessageParser();
        $this->compareRequestResults($parts, $parser->parseRequest($message));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Message\PeclHttpMessageParser::parseResponse
     * @dataProvider responseProvider
     */
    public function testParsesResponses($message, $parts)
    {
        $parser = new PeclHttpMessageParser();
        $this->compareResponseResults($parts, $parser->parseResponse($message));
    }
}
