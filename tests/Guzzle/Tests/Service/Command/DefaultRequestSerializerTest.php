<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\DefaultRequestSerializer;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\HeaderVisitor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\DefaultRequestSerializer
 */
class DefaultRequestSerializerTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var EntityEnclosingRequest
     */
    protected $request;

    /**
     * @var \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand
     */
    protected $command;

    /**
     * @var Client
     */
    protected $/* Replaced /* Replaced /* Replaced client */ */ */;

    /**
     * @var DefaultRequestSerializer
     */
    protected $serializer;

    /**
     * @var Operation
     */
    protected $operation;

    public function setUp()
    {
        $this->serializer = DefaultRequestSerializer::getInstance();
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://foo.com/baz');
        $this->operation = new Operation(array('httpMethod' => 'POST'));
        $this->command = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand')
            ->setConstructorArgs(array(array(), $this->operation))
            ->getMockForAbstractClass();
        $this->command->setClient($this->/* Replaced /* Replaced /* Replaced client */ */ */);
    }

    public function testAllowsCustomVisitor()
    {
        $this->serializer->addVisitor('custom', new HeaderVisitor());
        $this->command['test'] = '123';
        $this->operation->addParam(new Parameter(array('name' => 'test', 'location' => 'custom')));
        $request = $this->serializer->prepare($this->command);
        $this->assertEquals('123', (string) $request->getHeader('test'));
    }

    public function testUsesRelativePath()
    {
        $this->operation->setUri('bar');
        $request = $this->serializer->prepare($this->command);
        $this->assertEquals('http://foo.com/baz/bar', (string) $request->getUrl());
    }

    public function testUsesRelativePathWithUriLocations()
    {
        $this->command['test'] = '123';
        $this->operation->setUri('bar/{test}');
        $this->operation->addParam(new Parameter(array('name' => 'test', 'location' => 'uri')));
        $request = $this->serializer->prepare($this->command);
        $this->assertEquals('http://foo.com/baz/bar/123', (string) $request->getUrl());
    }
}
