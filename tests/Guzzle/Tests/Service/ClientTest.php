<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ClosureLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\XmlDescriptionBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;

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
            'test_command' => new ApiCommand(array(
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

        $this->service = ServiceDescription::factory(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'TestData' . DIRECTORY_SEPARATOR . 'test_service.xml');
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
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\CommandSetException
     */
    public function testThrowsExceptionWhenExecutingMixedClientCommandSets()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');
        $otherClient = new Client('http://www.test-123.com/');

        // Create a command set and a command
        $set = new CommandSet();
        $cmd = new MockCommand();
        $set->addCommand($cmd);

        // Associate the other /* Replaced /* Replaced /* Replaced client */ */ */ with the command
        $cmd->setClient($otherClient);

        // Send the set with the wrong /* Replaced /* Replaced /* Replaced client */ */ */, causing an exception
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($set);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testThrowsExceptionWhenExecutingInvalidCommandSets()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute(new \stdClass());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     */
    public function testExecutesCommandSets()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber(new MockPlugin(array(
            new Response(200)
        )));

        // Create a command set and a command
        $set = new CommandSet();
        $cmd = new MockCommand();
        $set->addCommand($cmd);
        $this->assertSame($set, $/* Replaced /* Replaced /* Replaced client */ */ */->execute($set));

        // Make sure it sent
        $this->assertTrue($cmd->isExecuted());
        $this->assertTrue($cmd->isPrepared());
        $this->assertEquals(200, $cmd->getResponse()->getStatusCode());
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

        $this->assertNotSame($factory, $/* Replaced /* Replaced /* Replaced client */ */ */->getCommandFactory());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory', $/* Replaced /* Replaced /* Replaced client */ */ */->getCommandFactory());
        $array = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommandFactory()->getIterator()->getArrayCopy();
        $this->assertSame($array[0], $factory);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\ServiceDescriptionFactory', $array[1]);
        $this->assertSame($description, $array[1]->getServiceDescription());

        $description2 = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Description\\ServiceDescription');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($description2);
        $array = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommandFactory()->getIterator()->getArrayCopy();
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setMagicCallBehavior
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Command was not found matching foo
     */
    public function testMagicCallBehaviorEnsuresCommandExists()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($this->service);
        $/* Replaced /* Replaced /* Replaced client */ */ */->setMagicCallBehavior(Client::MAGIC_CALL_RETURN);
        $/* Replaced /* Replaced /* Replaced client */ */ */->foo();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::__call
     */
    public function testMagicCallBehaviorReturnReturnsCommands()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setMagicCallBehavior(Client::MAGIC_CALL_RETURN);
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($this->service);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand', $/* Replaced /* Replaced /* Replaced client */ */ */->mockCommand());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::__call
     */
    public function testMagicCallBehaviorExecuteExecutesCommands()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setMagicCallBehavior(Client::MAGIC_CALL_EXECUTE);
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($this->service);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber(new MockPlugin(array(new Response(200))));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response', $/* Replaced /* Replaced /* Replaced client */ */ */->mockCommand());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getCommandFactory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setCommandFactory
     */
    public function testOwnsCommandFactory()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory', $/* Replaced /* Replaced /* Replaced client */ */ */->getCommandFactory());
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */->getCommandFactory(), $/* Replaced /* Replaced /* Replaced client */ */ */->getCommandFactory());

        $mock = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\Factory\\CompositeFactory');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setCommandFactory($mock);
        $this->assertSame($mock, $/* Replaced /* Replaced /* Replaced client */ */ */->getCommandFactory());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getCommand
     * @depends testMagicCallBehaviorExecuteExecutesCommands
     */
    public function testEnablesMagicMethodCallsOnCommandsIfEnabledOnClient()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient();
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('other_command');
        $this->assertNull($command->get('command.magic_method_call'));

        $/* Replaced /* Replaced /* Replaced client */ */ */->setMagicCallBehavior(Client::MAGIC_CALL_EXECUTE);
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('other_command');
        $this->assertTrue($command->get('command.magic_method_call'));
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
}
