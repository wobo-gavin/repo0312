<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\Sub\Sub;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class CommandTest extends AbstractCommandTest
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::init
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::canBatch
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::isPrepared
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::isExecuted
     */
    public function testConstructorAddsDefaultParams()
    {
        $command = new MockCommand();
        $this->assertEquals('123', $command->get('test'));
        $this->assertTrue($command->canBatch());
        $this->assertFalse($command->isPrepared());
        $this->assertFalse($command->isExecuted());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getName
     */
    public function testDeterminesShortName()
    {
        $api = new ApiCommand(array(
            'name' => 'foobar'
        ));
        $command = new MockCommand(array(), $api);
        $this->assertEquals('foobar', $command->getName());

        $command = new MockCommand();
        $this->assertEquals('mock_command', $command->getName());

        $command = new Sub();
        $this->assertEquals('sub.sub', $command->getName());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getRequest
     * @expectedException RuntimeException
     */
    public function testGetRequestThrowsExceptionBeforePreparation()
    {
        $command = new MockCommand();
        $command->getRequest();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getResponse
     * @expectedException RuntimeException
     */
    public function testGetResponseThrowsExceptionBeforePreparation()
    {
        $command = new MockCommand();
        $command->getResponse();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getResult
     * @expectedException RuntimeException
     */
    public function testGetResultThrowsExceptionBeforePreparation()
    {
        $command = new MockCommand();
        $command->getResult();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::setClient
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getClient
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::prepare
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::isPrepared
     */
    public function testSetClient()
    {
        $command = new MockCommand();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();

        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertEquals($/* Replaced /* Replaced /* Replaced client */ */ */, $command->getClient());

        unset($/* Replaced /* Replaced /* Replaced client */ */ */);
        unset($command);

        $command = new MockCommand();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();

        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */)->prepare();
        $this->assertEquals($/* Replaced /* Replaced /* Replaced client */ */ */, $command->getClient());
        $this->assertTrue($command->isPrepared());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::execute
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::setClient
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getRequest
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getResponse
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getResult
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::prepare
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::process
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::prepare
     *
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     */
    public function testExecute()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();

        $response = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response(200, array(
            'Content-Type' => 'application/xml'
        ), '<xml><data>123</data></xml>');

        // Set a mock response
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach(function($subject, $event, $context) use ($response) {
            if ($event == 'request.create') {
                $context->setResponse($response, true);
            }
        });

        $command = new MockCommand();

        $this->assertSame($command, $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */));
        $this->assertSame($command, $command->execute()); // Implicitly calls prepare

        $this->assertTrue($command->isPrepared());
        $this->assertTrue($command->isExecuted());
        $this->assertSame($response, $command->getResponse());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Request', $command->getRequest());
        // Make sure that the result was automatically set to a SimpleXMLElement
        $this->assertInstanceOf('SimpleXMLElement', $command->getResult());
        $this->assertEquals('123', (string)$command->getResult()->data);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::process
     */
    public function testProcessResponseIsNotXml()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();

        // Set a mock response
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach(function($subject, $event, $context) {
            if ($event == 'request.create') {
                $context->setResponse(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response(200, array(
                    'Content-Type' => 'application/octect-stream'
                ), 'abc,def,ghi'));
            }
        });

        $command = new MockCommand();
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        // Make sure that the result was not converted to XML
        $this->assertFalse($command->getResult() instanceof \SimpleXMLElement);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::execute
     * @expectedException RuntimeException
     */
    public function testExecuteThrowsExceptionWhenNoClientIsSet()
    {
        $command = new MockCommand();
        $command->execute();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::prepare
     * @expectedException RuntimeException
     */
    public function testPrepareThrowsExceptionWhenNoClientIsSet()
    {
        $command = new MockCommand();
        $command->prepare();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::prepare
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getRequestHeaders
     */
    public function testCommandsAllowsCustomRequestHeaders()
    {
        $command = new MockCommand();
        $command->getRequestHeaders()->set('test', '123');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection', $command->getRequestHeaders());
        $this->assertEquals('123', $command->getRequestHeaders()->get('test'));

        $command->setClient($this->getClient())->prepare();
        $this->assertEquals('123', $command->getRequest()->getHeaders()->get('test'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand
     */
    public function testCommandsUsesApiCommand()
    {
        $api = new ApiCommand(array(
            'name' => 'foobar',
            'method' => 'POST',
            'min_args' => 1,
            'can_batch' => true,
            'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\MockCommand',
            'args' => array(
                'test' => array(
                    'default' => '123',
                    'type' => 'string'
                )
        )));

        $command = new MockCommand(array(), $api);
        $this->assertSame($api, $command->getApiCommand());
        $command->setClient($this->getClient())->prepare();
        $this->assertEquals('123', $command->get('test'));
        $this->assertSame($api, $command->getApiCommand($api));
    }
}