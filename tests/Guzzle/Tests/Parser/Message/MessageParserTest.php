<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Parser\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Message\MessageParser;

class MessageParserTest extends MessageParserProvider
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Message\AbstractMessageParser::getUrlPartsFromMessage
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Message\MessageParser::parseMessage
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Message\MessageParser::parseRequest
     * @dataProvider requestProvider
     */
    public function testParsesRequests($message, $parts)
    {
        $parser = new MessageParser();
        $this->compareRequestResults($parts, $parser->parseRequest($message));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Message\MessageParser::parseMessage
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Message\MessageParser::parseResponse
     * @dataProvider responseProvider
     */
    public function testParsesResponses($message, $parts)
    {
        $parser = new MessageParser();
        $this->compareResponseResults($parts, $parser->parseResponse($message));
    }
}
