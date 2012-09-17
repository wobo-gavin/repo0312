<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Mock\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\Sub\Sub;

class CommandTest extends AbstractCommandTest
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::init
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::isPrepared
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::isExecuted
     */
    public function testConstructorAddsDefaultParams()
    {
        $command = new MockCommand();
        $this->assertEquals('123', $command->get('test'));
        $this->assertFalse($command->isPrepared());
        $this->assertFalse($command->isExecuted());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getName
     */
    public function testDeterminesShortName()
    {
        $api = new Operation(array('name' => 'foobar'));
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     */
    public function testExecute()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $response = new Response(200, array(
            'Content-Type' => 'application/xml'
        ), '<xml><data>123</data></xml>');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array($response));
        $command = new MockCommand();
        $this->assertSame($command, $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */));

        // Returns the result of the command
        $this->assertInstanceOf('SimpleXMLElement', $command->execute());

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
    public function testConvertsJsonResponsesToArray()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array(
            new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response(200, array(
                'Content-Type' => 'application/json'
                ), '{ "key": "Hi!" }'
            )
        ));
        $command = new MockCommand();
        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
        $command->execute();
        $this->assertEquals(array(
            'key' => 'Hi!'
        ), $command->getResult());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::process
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\JsonException
     */
    public function testConvertsInvalidJsonResponsesToArray()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array(
            new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response(200, array(
                'Content-Type' => 'application/json'
                ), '{ "key": "Hi!" }invalid'
            )
        ));
        $command = new MockCommand();
        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
        $command->execute();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::process
     */
    public function testProcessResponseIsNotXml()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array(
            new Response(200, array(
                'Content-Type' => 'application/octet-stream'
            ), 'abc,def,ghi')
        ));
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
        $this->assertEquals('123', (string) $command->getRequest()->getHeader('test'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::__construct
     */
    public function testCommandsAllowsCustomRequestHeadersAsArray()
    {
        $command = new MockCommand(array('headers' => array('Foo' => 'Bar')));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection', $command->getRequestHeaders());
        $this->assertEquals('Bar', $command->getRequestHeaders()->get('Foo'));
    }

    private function getOperation()
    {
        return new Operation(array(
            'name'       => 'foobar',
            'httpMethod' => 'POST',
            'class'      => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\MockCommand',
            'parameters' => array(
                'test' => array(
                    'default' => '123',
                    'type'    => 'string'
                )
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand
     */
    public function testCommandsUsesOperation()
    {
        $api = $this->getOperation();
        $command = new MockCommand(array(), $api);
        $this->assertSame($api, $command->getOperation());
        $command->setClient($this->getClient())->prepare();
        $this->assertEquals('123', $command->get('test'));
        $this->assertSame($api, $command->getOperation($api));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::__clone
     */
    public function testCloneMakesNewRequest()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $command = new MockCommand(array(), $this->getOperation());
        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);

        $command->prepare();
        $this->assertTrue($command->isPrepared());

        $command2 = clone $command;
        $this->assertFalse($command2->isPrepared());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::setOnComplete
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::getResult
     */
    public function testHasOnCompleteMethod()
    {
        $that = $this;
        $called = 0;

        $testFunction = function($command) use (&$called, $that) {
            $called++;
            $that->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface', $command);
        };

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $command = new MockCommand(array(
            'command.on_complete' => $testFunction
        ), $this->getOperation());
        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);

        $command->prepare()->setResponse(new Response(200), true);
        $command->execute();
        $this->assertEquals(1, $called);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::setOnComplete
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testOnCompleteMustBeCallable()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $command = new MockCommand();
        $command->setOnComplete('foo');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::setResult
     */
    public function testCanSetResultManually()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber(new MockPlugin(array(
            new Response(200)
        )));
        $command = new MockCommand();
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $command->setResult('foo!');
        $this->assertEquals('foo!', $command->getResult());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand
     */
    public function testCanInitConfig()
    {
        $command = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\AbstractCommand')
            ->setConstructorArgs(array(array(
                'foo' => 'bar'
            ), new Operation(array(
                'parameters' => array(
                    'baz' => new Parameter(array(
                        'default' => 'baaar'
                    ))
                )
            ))))
            ->getMockForAbstractClass();

        $this->assertEquals('bar', $command['foo']);
        $this->assertEquals('baaar', $command['baz']);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::prepare
     */
    public function testAddsCurlOptionsToRequestsWhenPreparing()
    {
        $command = new MockCommand(array(
            'foo' => 'bar',
            'curl.CURLOPT_PROXYPORT' => 8080
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
        $request = $command->prepare();
        $this->assertEquals(8080, $request->getCurlOptions()->get(CURLOPT_PROXYPORT));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::__invoke
     */
    public function testIsInvokable()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $response = new Response(200);
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array($response));
        $command = new MockCommand();
        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
        // Returns the result of the command
        $this->assertSame($response, $command());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand::createOperation
     */
    public function testCreatesDefaultOperation()
    {
        $command = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand')->getMockForAbstractClass();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation', $command->getOperation());
    }
}
