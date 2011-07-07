<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockObserver;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Pool\Pool;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\XmlDescriptionBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class CommandSetTest extends AbstractCommandTest
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::hasCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::addCommand
     */
    public function test__construct()
    {
        $pool = new Pool();
        $cmd = new MockCommand();
        $commandSet = new CommandSet(array($cmd), $pool);
        $this->assertTrue($commandSet->hasCommand($cmd));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::hasCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::addCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::removeCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::getParallelCommands
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::getSerialCommands
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::count
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::getIterator
     */
    public function testAllowsCommandManipulationAndIntrospection()
    {
        $commandSet = new CommandSet();

        // Check when no commands are set
        $this->assertEquals(array(), $commandSet->getSerialCommands());

        // Create some mock commands
        $command1 = new MockCommand();
        $command2 = new MockCommand();
        $command2->setCanBatch(false);

        // Check the fluent interface
        $this->assertEquals($commandSet, $commandSet->addCommand($command1));
        $commandSet->addCommand($command2);

        // Check that the commands are registered and findable
        $this->assertTrue($commandSet->hasCommand($command1));
        $this->assertTrue($commandSet->hasCommand($command2));
        $this->assertTrue($commandSet->hasCommand('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\MockCommand'));

        // Test that the Countable interface is working
        $this->assertEquals(2, count($commandSet));
        // Test that the IteratorAggregate interface is working
        $this->assertInstanceOf('ArrayIterator', $commandSet->getIterator());
        $this->assertEquals(2, count($commandSet->getIterator()));

        // Check that filtering by command type works-- serial vs parallel
        $this->assertEquals(array($command1), $commandSet->getParallelCommands());
        $this->assertEquals(array($command2), $commandSet->getSerialCommands());

        // Remove the command by object
        $commandSet->removeCommand($command1);
        $this->assertFalse($commandSet->hasCommand($command1));
        $this->assertTrue($commandSet->hasCommand($command2));

        // Remove the command by class
        $commandSet->removeCommand('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\MockCommand');
        $this->assertFalse($commandSet->hasCommand($command2));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::execute
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSetException
     */
    public function testThrowsExceptionWhenAnyCommandHasNoClient()
    {
        $cmd = new MockCommand;
        $commandSet = new CommandSet(array($cmd));
        try {
            $commandSet->execute();
            $this->fail('CommandSetException not thrown when a command did not have a /* Replaced /* Replaced /* Replaced client */ */ */');
        } catch (\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSetException $e) {
            $this->assertEquals(array($cmd), $e->getCommands());
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::execute
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet::update
     */
    public function testExecutesCommands()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $observer = new MockObserver();

        // Create a Mock response
        $response = new Response(200, array(
            'Content-Type' => 'application/xml'
        ), '<xml><data>123</data></xml>');

        // Set a mock response for each request from the Client
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach(function($subject, $event, $context) use ($response) {
            if ($event == 'request.create') {
                $context->setResponse($response, true);
            }
        });

        $command1 = new MockCommand();
        $command1->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
        $command2 = new MockCommand();
        $command2->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
        $command2->setCanBatch(false);
        $command3 = new MockCommand();
        $command3->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);

        $commandSet = new CommandSet(array($command1, $command2, $command3));
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach($observer);
        $commandSet->execute();

        $this->assertTrue($command1->isExecuted());
        $this->assertTrue($command2->isExecuted());
        $this->assertTrue($command3->isExecuted());
        $this->assertTrue($command1->isPrepared());
        $this->assertTrue($command2->isPrepared());
        $this->assertTrue($command3->isPrepared());

        $this->assertEquals($response, $command1->getResponse());
        $this->assertEquals($response, $command2->getResponse());

        $this->assertEquals(3, count(array_filter($observer->events, function($e) {
            return $e == 'command.before_send';
        })));

        $this->assertEquals(3, count(array_filter($observer->events, function($e) {
            return $e == 'command.after_send';
        })));

        // make sure the command set was detached as a listener
        $this->assertFalse($command1->getRequest()->getEventManager()->hasObserver('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\CommandSet'));
        // make sure that the command reference was removed
        $this->assertFalse($command1->getRequest()->getParams()->hasKey('command'));

        // Make sure that the command.after_send events are staggered, meaning they happened as requests completed
        $lastEvent = '';
        foreach ($observer->events as $e) {
            if ($lastEvent == 'command.after_send' && $e == 'command.after_send') {
                $this->fail('Not completing commands as they complete: ' . var_export($observer->events, true));
            }
            $lastEvent = $e;
        }
    }
}