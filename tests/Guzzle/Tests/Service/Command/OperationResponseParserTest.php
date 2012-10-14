<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\OperationResponseParser;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\OperationCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\StatusCodeVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\ReasonPhraseVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\BodyVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\Model;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\OperationResponseParser
 */
class OperationResponseParserTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testHasVisitors()
    {
        $p = new OperationResponseParser();
        $visitor = new BodyVisitor();
        $p->addVisitor('foo', $visitor);
        $this->assertSame(array('foo' => $visitor), $this->readAttribute($p, 'visitors'));
    }

    public function testUsesParentParser()
    {
        $p = new OperationResponseParser();
        $operation = new Operation();
        $operation->setServiceDescription(new ServiceDescription());
        $op = new OperationCommand(array(), $operation);
        $op->setResponseParser($p)->setClient(new Client());
        $op->prepare()->setResponse(new Response(200, array('Content-Type' => 'application/xml'), '<F><B>C</B></F>'), true);
        $this->assertInstanceOf('SimpleXMLElement', $op->execute());
    }

    public function testConvertsSimpleXMLElementToArrayWhenModelIsFound()
    {
        $parser = new OperationResponseParser();
        $op = new OperationCommand(array(), $this->getDescription()->getOperation('test'));
        $op->setResponseParser($parser)->setClient(new Client());
        $op->prepare()->setResponse(new Response(200, array('Content-Type' => 'application/xml'), '<F><B>C</B></F>'), true);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\Model', $op->execute());
        $this->assertEquals('C', $op->getResult()->get('B'));
    }

    public function testUsesNativeResultWhenInstructed()
    {
        $parser = new OperationResponseParser();
        $op = new OperationCommand(array(), $this->getDescription()->getOperation('test'));
        $op->setResponseParser($parser)->setClient(new Client());
        $op->prepare()->setResponse(new Response(200, array('Content-Type' => 'application/xml'), '<F><B>C</B></F>'), true);
        $op->set(AbstractCommand::RESPONSE_PROCESSING, 'native');
        $this->assertInstanceOf('SimpleXMLElement', $op->execute());
    }

    public function testVisitsLocations()
    {
        $parser = new OperationResponseParser();
        $parser->addVisitor('statusCode', new StatusCodeVisitor());
        $parser->addVisitor('reasonPhrase', new ReasonPhraseVisitor());
        $op = new OperationCommand(array(), $this->getDescription()->getOperation('test'));
        $op->setResponseParser($parser)->setClient(new Client());
        $op->prepare()->setResponse(new Response(201), true);
        $result = $op->execute();
        $this->assertEquals(201, $result['code']);
        $this->assertEquals('Created', $result['phrase']);
    }

    public function testVisitsLocationsForJsonResponse()
    {
        $parser = new OperationResponseParser();
        $op = new OperationCommand(array(), $this->getDescription()->getOperation('test'));
        $op->setResponseParser($parser)->setClient(new Client());
        $op->prepare()->setResponse(new Response(200, array(
            'Content-Type' => 'application/json'
        ), '{"baz":"bar"}'), true);
        $result = $op->execute();
        $this->assertEquals(array('baz' => 'bar'), $result->toArray());
    }

    public function testSkipsUnkownModels()
    {
        $parser = new OperationResponseParser();
        $operation = $this->getDescription()->getOperation('test');
        $operation->setResponseClass('Baz')->setResponseType('model');
        $op = new OperationCommand(array(), $operation);
        $op->setResponseParser($parser)->setClient(new Client());
        $op->prepare()->setResponse(new Response(201), true);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response', $op->execute());
    }

    protected function getDescription()
    {
        return new ServiceDescription(array(
            'operations' => array('test' => array('responseClass' => 'Foo')),
            'models' => array(
                'Foo' => array(
                    'name'       => 'Foo',
                    'type'       => 'object',
                    'properties' => array(
                        'baz'    => array('type' => 'string'),
                        'code'   => array('location' => 'statusCode'),
                        'phrase' => array('location' => 'reasonPhrase'),
                    )
                )
            )
        ));
    }
}
