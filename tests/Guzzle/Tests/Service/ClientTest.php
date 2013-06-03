<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Inflection\Inflector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Mock\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\CommandTransferException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\ResourceIteratorClassFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand;

/**
 * @group server
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client
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

    public function testAllowsCustomClientParameters()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient(null, array(
            Client::COMMAND_PARAMS => array(AbstractCommand::RESPONSE_PROCESSING => 'foo')
        ));
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('mock_command');
        $this->assertEquals('foo', $command->get(AbstractCommand::RESPONSE_PROCESSING));
    }

    public function testFactoryCreatesClient()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = Client::factory(array(
            'base_url' => 'http://www.test.com/',
            'test' => '123'
        ));

        $this->assertEquals('http://www.test.com/', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertEquals('123', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('test'));
    }

    public function testFactoryDoesNotRequireBaseUrl()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = Client::factory();
    }

    public function testDescribesEvents()
    {
        $this->assertInternalType('array', Client::getAllEvents());
    }

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
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testThrowsExceptionWhenInvalidCommandIsExecuted()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute(new \stdClass());
    }

    /**
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

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('foo', array('acl' => '123'));
        $this->assertSame($mockCommand, $command);
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('foo', array('acl' => '123'));
        $this->assertSame($mockCommand, $command);
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $command->getClient());
    }

    public function testOwnsServiceDescription()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $this->assertNull($/* Replaced /* Replaced /* Replaced client */ */ */->getDescription());

        $description = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Description\\ServiceDescription');
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($description));
        $this->assertSame($description, $/* Replaced /* Replaced /* Replaced client */ */ */->getDescription());
    }

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

    public function testClientCreatesIteratorsWithNoOptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $iterator = $/* Replaced /* Replaced /* Replaced client */ */ */->getIterator('mock_command');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model\MockCommandIterator', $iterator);
    }

    public function testClientCreatesIteratorsWithCommands()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $command = new MockCommand();
        $iterator = $/* Replaced /* Replaced /* Replaced client */ */ */->getIterator($command);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Model\MockCommandIterator', $iterator);
        $iteratorCommand = $this->readAttribute($iterator, 'originalCommand');
        $this->assertSame($command, $iteratorCommand);
    }

    public function testClientHoldsInflector()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Inflection\MemoizingInflector', $/* Replaced /* Replaced /* Replaced client */ */ */->getInflector());

        $inflector = new Inflector();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setInflector($inflector);
        $this->assertSame($inflector, $/* Replaced /* Replaced /* Replaced client */ */ */->getInflector());
    }

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

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException
     */
    public function testWrapsSingleCommandExceptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient('http://foobaz.com');
        $mock = new MockPlugin(array(new Response(401)));
        $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute(new MockCommand());
    }

    public function testWrapsMultipleCommandExceptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient('http://foobaz.com');
        $mock = new MockPlugin(array(new Response(200), new Response(200), new Response(404), new Response(500)));
        $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($mock);

        $cmds = array(new MockCommand(), new MockCommand(), new MockCommand(), new MockCommand());
        try {
            $/* Replaced /* Replaced /* Replaced client */ */ */->execute($cmds);
        } catch (CommandTransferException $e) {
            $this->assertEquals(2, count($e->getFailedRequests()));
            $this->assertEquals(2, count($e->getSuccessfulRequests()));
            $this->assertEquals(2, count($e->getFailedCommands()));
            $this->assertEquals(2, count($e->getSuccessfulCommands()));

            foreach ($e->getSuccessfulCommands() as $c) {
                $this->assertTrue($c->getResponse()->isSuccessful());
            }

            foreach ($e->getFailedCommands() as $c) {
                $this->assertFalse($c->getRequest()->getResponse()->isSuccessful());
            }
        }
    }
}
