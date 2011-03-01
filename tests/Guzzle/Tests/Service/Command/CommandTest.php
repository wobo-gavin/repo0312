<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ApiCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;

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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getRequest
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandException
     */
    public function testGetRequestThrowsExceptionBeforePreparation()
    {
        $command = new MockCommand();
        $command->getRequest();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getResponse
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandException
     */
    public function testGetResponseThrowsExceptionBeforePreparation()
    {
        $command = new MockCommand();
        $command->getResponse();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getResult
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandException
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

        $command->prepare($/* Replaced /* Replaced /* Replaced client */ */ */);
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
        $/* Replaced /* Replaced /* Replaced client */ */ */->getCreateRequestChain()->addFilter(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilter(array(
            'callback' => function($filter, $command) use ($response) {
                $command->setResponse($response);
            }
        )));

        $command = new MockCommand();

        $this->assertEquals($command, $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */));
        $this->assertEquals($command, $command->execute()); // Implicitly calls prepare

        $this->assertTrue($command->isPrepared());
        $this->assertTrue($command->isExecuted());
        $this->assertEquals($response, $command->getResponse());
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
        $/* Replaced /* Replaced /* Replaced client */ */ */->getCreateRequestChain()->addFilter(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilter(array(
            'callback' => function($filter, $command) {
                $command->setResponse(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response(200, array(
                    'Content-Type' => 'application/octect-stream'
                ), 'abc,def,ghi'));
            }
        )));

        $command = new MockCommand();
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        // Make sure that the result was not converted to XML
        $this->assertFalse($command->getResult() instanceof \SimpleXMLElement);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::execute
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandException
     */
    public function testExecuteThrowsExceptionWhenNoClientIsSet()
    {
        $command = new MockCommand();
        $command->execute();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::prepare
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandException
     */
    public function testPrepareThrowsExceptionWhenNoClientIsSet()
    {
        $command = new MockCommand();
        $command->prepare();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::prepare
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::setRequestHeader
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getRequestHeaders
     */
    public function testCommandsAllowsCustomRequestHeaders()
    {
        $command = new MockCommand();
        $command->setRequestHeader('test', '123');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection', $command->getRequestHeaders());
        $this->assertEquals('123', $command->getRequestHeaders()->get('test'));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();

        $command->prepare($/* Replaced /* Replaced /* Replaced client */ */ */);
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
            'concrete_command_class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\MockCommand',
            'args' => array(
                'test' => array(
                    'default' => '123',
                    'type' => 'string'
                )
        )));

        $command = new MockCommand(array(), $api);
        $this->assertSame($api, $command->getApiCommand());
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $command->prepare($/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertEquals('123', $command->get('test'));

        $this->assertSame($command, $command->setApiCommand($api));
        $this->assertSame($api, $command->getApiCommand($api));
    }
}