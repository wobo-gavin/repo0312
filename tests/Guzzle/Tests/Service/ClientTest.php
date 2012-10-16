<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Inflection\Inflector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Mock\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\ResourceIteratorClassFactory;

/**
 * @group server
 */
class ClientTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $service;
    protected $serviceTest;

    public function setUp()
    {
        $this->serviceTest = new ServiceDescription(array(
            'test_command' => new Operation(array(
                'doc' => 'documentationForCommand',
                'method' => 'DELETE',
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\MockCommand',
                'args' => array(
                    'bucket' => array(
                        'required' => true
                    ),
                    'key' => array(
                        'required' => true
                    )
                )
            ))
        ));

        $this->service = ServiceDescription::factory(__DIR__ . '/../TestData/test_service.json');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::factory
     */
    public function testFactoryCreatesClient()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = Client::factory(array(
            'base_url' => 'http://www.test.com/',
            'test' => '123'
        ));

        $this->assertEquals('http://www.test.com/', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertEquals('123', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('test'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getAllEvents
     */
    public function testDescribesEvents()
    {
        $this->assertInternalType('array', Client::getAllEvents());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     */
    public function testExecutesCommands()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $cmd = new MockCommand();
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($cmd);

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Response', $cmd->getResponse());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Response', $cmd->getResult());
        $this->assertEquals(1, count($this->getServer()->getReceivedRequests(false)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     */
    public function testExecutesCommandsWithArray()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber(new MockPlugin(array(
            new Response(200),
            new Response(200)
        )));

        // Create a command set and a command
        $set = array(new MockCommand(), new MockCommand());
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($set);

        // Make sure it sent
        $this->assertTrue($set[0]->isExecuted());
        $this->assertTrue($set[1]->isExecuted());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testThrowsExceptionWhenInvalidCommandIsExecuted()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute(new \stdClass());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getCommand
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionWhenMissingCommand()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();

        $mock = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\FactoryInterface');
        $mock->expects($this->any())
             ->method('factory')
             ->with($this->equalTo('test'))
             ->will($this->returnValue(null));

        $/* Replaced /* Replaced /* Replaced client */ */ */->setCommandFactory($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('test');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getCommand
     */
    public function testCreatesCommandsUsingCommandFactory()
    {
        $mockCommand = new MockCommand();

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $mock = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\FactoryInterface');
        $mock->expects($this->any())
             ->method('factory')
             ->with($this->equalTo('foo'))
             ->will($this->returnValue($mockCommand));

        $/* Replaced /* Replaced /* Replaced client */ */ */->setCommandFactory($mock);

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('foo', array(
            'acl' => '123'
        ));

        $this->assertSame($mockCommand, $command);
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $command->getClient());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getDescription
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setDescription
     */
    public function testOwnsServiceDescription()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $this->assertNull($/* Replaced /* Replaced /* Replaced client */ */ */->getDescription());

        $description = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Description\\ServiceDescription');
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($description));
        $this->assertSame($description, $/* Replaced /* Replaced /* Replaced client */ */ */->getDescription());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setDescription
     */
    public function testSettingServiceDescriptionUpdatesFactories()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $factory = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\MapFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setCommandFactory($factory);

        $description = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Description\\ServiceDescription');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($description);

        $cf = $this->readAttribute($/* Replaced /* Replaced /* Replaced client */ */ */, 'commandFactory');
        $this->assertNotSame($factory, $cf);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory', $cf);

        $array = $cf->getIterator()->getArrayCopy();
        $this->assertSame($array[0], $factory);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\ServiceDescriptionFactory', $array[1]);
        $this->assertSame($description, $array[1]->getServiceDescription());

        $description2 = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Description\\ServiceDescription');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($description2);

        $cf = $this->readAttribute($/* Replaced /* Replaced /* Replaced client */ */ */, 'commandFactory');
        $array = $cf->getIterator()->getArrayCopy();
        $this->assertSame($array[0], $factory);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\ServiceDescriptionFactory', $array[1]);
        $this->assertSame($description2, $array[1]->getServiceDescription());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::__call
     * @expectedException BadMethodCallException
     */
    public function testMagicCallBehaviorIsDisabledByDefault()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->foo();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::__call
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::enableMagicMethods
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Command was not found matching foo
     */
    public function testMagicCallBehaviorEnsuresCommandExists()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($this->service);
        $/* Replaced /* Replaced /* Replaced client */ */ */->enableMagicMethods(true);
        $/* Replaced /* Replaced /* Replaced client */ */ */->foo();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::__call
     */
    public function testMagicCallBehaviorExecuteExecutesCommands()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $/* Replaced /* Replaced /* Replaced client */ */ */->enableMagicMethods(true);
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($this->service);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber(new MockPlugin(array(new Response(200))));
        $cmd = $/* Replaced /* Replaced /* Replaced client */ */ */->mockCommand();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand', $cmd);
        $this->assertFalse($cmd->isExecuted());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getCommandFactory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setCommandFactory
     */
    public function testOwnsCommandFactory()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $method = new \ReflectionMethod($/* Replaced /* Replaced /* Replaced client */ */ */, 'getCommandFactory');
        $method->setAccessible(TRUE);
        $cf1 = $method->invoke($/* Replaced /* Replaced /* Replaced client */ */ */);

        $cf = $this->readAttribute($/* Replaced /* Replaced /* Replaced client */ */ */, 'commandFactory');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory', $cf);
        $this->assertSame($method->invoke($/* Replaced /* Replaced /* Replaced client */ */ */), $cf1);

        $mock = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setCommandFactory($mock);
        $this->assertSame($mock, $this->readAttribute($/* Replaced /* Replaced /* Replaced client */ */ */, 'commandFactory'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getResourceIteratorFactory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setResourceIteratorFactory
     */
    public function testOwnsResourceIteratorFactory()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();

        $method = new \ReflectionMethod($/* Replaced /* Replaced /* Replaced client */ */ */, 'getResourceIteratorFactory');
        $method->setAccessible(TRUE);
        $rf1 = $method->invoke($/* Replaced /* Replaced /* Replaced client */ */ */);

        $rf = $this->readAttribute($/* Replaced /* Replaced /* Replaced client */ */ */, 'resourceIteratorFactory');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Resource\\ResourceIteratorClassFactory', $rf);
        $this->assertSame($rf1, $rf);

        $rf = new ResourceIteratorClassFactory('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setResourceIteratorFactory($rf);
        $this->assertNotSame($rf1, $rf);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     */
    public function testClientResetsRequestsBeforeExecutingCommands()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nHi",
            "HTTP/1.1 200 OK\r\nContent-Length: 1\r\n\r\nI"
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient($this->getServer()->getUrl());

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('mock_command');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertEquals('I', $command->getResponse()->getBody(true));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getIterator
     */
    public function testClientCreatesIterators()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();

        $iterator = $/* Replaced /* Replaced /* Replaced client */ */ */->getIterator('mock_command', array(
            'foo' => 'bar'
        ), array(
            'limit' => 10
        ));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model\MockCommandIterator', $iterator);
        $this->assertEquals(10, $this->readAttribute($iterator, 'limit'));

        $command = $this->readAttribute($iterator, 'originalCommand');
        $this->assertEquals('bar', $command->get('foo'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getIterator
     */
    public function testClientCreatesIteratorsWithNoOptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $iterator = $/* Replaced /* Replaced /* Replaced client */ */ */->getIterator('mock_command');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model\MockCommandIterator', $iterator);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getIterator
     */
    public function testClientCreatesIteratorsWithCommands()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $command = new MockCommand();
        $iterator = $/* Replaced /* Replaced /* Replaced client */ */ */->getIterator($command);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model\MockCommandIterator', $iterator);
        $iteratorCommand = $this->readAttribute($iterator, 'originalCommand');
        $this->assertSame($command, $iteratorCommand);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getInflector
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setInflector
     */
    public function testClientHoldsInflector()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Inflection\MemoizingInflector', $/* Replaced /* Replaced /* Replaced client */ */ */->getInflector());

        $inflector = new Inflector();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setInflector($inflector);
        $this->assertSame($inflector, $/* Replaced /* Replaced /* Replaced client */ */ */->getInflector());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getCommand
     */
    public function testClientAddsGlobalCommandOptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient('http://www.foo.com', array(
            Client::COMMAND_PARAMS => array(
                'mesa' => 'bar'
            )
        ));
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('mock_command');
        $this->assertEquals('bar', $command->get('mesa'));
    }

    public function testSupportsServiceDescriptionBaseUrls()
    {
        $description = new ServiceDescription(array('baseUrl' => 'http://foo.com'));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($description);
        $this->assertEquals('http://foo.com', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
    }

    public function testMergesDefaultCommandParamsCorrectly()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient('http://www.foo.com', array(
            Client::COMMAND_PARAMS => array(
                'mesa' => 'bar',
                'jar'  => 'jar'
            )
        ));
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('mock_command', array('jar' => 'test'));
        $this->assertEquals('bar', $command->get('mesa'));
        $this->assertEquals('test', $command->get('jar'));
    }
}
