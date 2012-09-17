<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\DefaultResponseParser;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\OperationCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\DefaultResponseParser
 */
class DefaultResponseParserTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testParsesXmlResponses()
    {
        $op = new OperationCommand(array(), new Operation());
        $op->setClient(new Client());
        $request = $op->prepare();
        $request->setResponse(new Response(200, array(
            'Content-Type' => 'application/xml'
        ), '<Foo><Baz>Bar</Baz></Foo>'), true);
        $this->assertInstanceOf('SimpleXMLElement', $op->execute());
    }

    public function testParsesJsonResponses()
    {
        $op = new OperationCommand(array(), new Operation());
        $op->setClient(new Client());
        $request = $op->prepare();
        $request->setResponse(new Response(200, array(
            'Content-Type' => 'application/json'
        ), '{"Baz":"Bar"}'), true);
        $this->assertEquals(array('Baz' => 'Bar'), $op->execute());
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\JsonException
     */
    public function testThrowsExceptionWhenParsingJsonFails()
    {
        $op = new OperationCommand(array(), new Operation());
        $op->setClient(new Client());
        $request = $op->prepare();
        $request->setResponse(new Response(200, array('Content-Type' => 'application/json'), '{"Baz":ddw}'), true);
        $op->execute();
    }
}
