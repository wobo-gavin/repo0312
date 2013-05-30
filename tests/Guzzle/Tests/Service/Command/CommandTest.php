<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Mock\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\SchemaValidator;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\Sub\Sub;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand
 */
class CommandTest extends AbstractCommandTest
{
    public function testConstructorAddsDefaultParams()
    {
        $command = new MockCommand();
        $this->assertEquals('123', $command->get('test'));
        $this->assertFalse($command->isPrepared());
        $this->assertFalse($command->isExecuted());
    }

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
     * @expectedException RuntimeException
     */
    public function testGetRequestThrowsExceptionBeforePreparation()
    {
        $command = new MockCommand();
        $command->getRequest();
    }

    public function testGetResponseExecutesCommandsWhenNeeded()
    {
        $response = new Response(200);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array($response));
        $command = new MockCommand();
        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertSame($response, $command->getResponse());
        $this->assertSame($response, $command->getResponse());
    }

    public function testGetResultExecutesCommandsWhenNeeded()
    {
        $response = new Response(200);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array($response));
        $command = new MockCommand();
        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertSame($response, $command->getResult());
        $this->assertSame($response, $command->getResult());
    }

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
        $this->assertEquals('123', (string) $command->getResult()->data);
    }

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
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\RuntimeException
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
     * @expectedException RuntimeException
     */
    public function testExecuteThrowsExceptionWhenNoClientIsSet()
    {
        $command = new MockCommand();
        $command->execute();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testPrepareThrowsExceptionWhenNoClientIsSet()
    {
        $command = new MockCommand();
        $command->prepare();
    }

    public function testCommandsAllowsCustomRequestHeaders()
    {
        $command = new MockCommand();
        $command->getRequestHeaders()->set('test', '123');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection', $command->getRequestHeaders());
        $this->assertEquals('123', $command->getRequestHeaders()->get('test'));

        $command->setClient($this->getClient())->prepare();
        $this->assertEquals('123', (string) $command->getRequest()->getHeader('test'));
    }

    public function testCommandsAllowsCustomRequestHeadersAsArray()
    {
        $command = new MockCommand(array(AbstractCommand::HEADERS_OPTION => array('Foo' => 'Bar')));
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

    public function testCommandsUsesOperation()
    {
        $api = $this->getOperation();
        $command = new MockCommand(array(), $api);
        $this->assertSame($api, $command->getOperation());
        $command->setClient($this->getClient())->prepare();
        $this->assertEquals('123', $command->get('test'));
        $this->assertSame($api, $command->getOperation($api));
    }

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
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testOnCompleteMustBeCallable()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getClient();
        $command = new MockCommand();
        $command->setOnComplete('foo');
    }

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

    public function testAddsCurlOptionsToRequestsWhenPreparing()
    {
        $command = new MockCommand(array(
            'foo' => 'bar',
            'curl.options' => array('CURLOPT_PROXYPORT' => 8080)
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
        $request = $command->prepare();
        $this->assertEquals(8080, $request->getCurlOptions()->get(CURLOPT_PROXYPORT));
    }

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

    public function testCreatesDefaultOperation()
    {
        $command = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand')->getMockForAbstractClass();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation', $command->getOperation());
    }

    public function testAllowsValidatorToBeInjected()
    {
        $command = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand')->getMockForAbstractClass();
        $v = new SchemaValidator();
        $command->setValidator($v);
        $this->assertSame($v, $this->readAttribute($command, 'validator'));
    }

    public function testCanDisableValidation()
    {
        $command = new MockCommand();
        $command->setClient(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client());
        $v = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\SchemaValidator')
            ->setMethods(array('validate'))
            ->getMock();
        $v->expects($this->never())->method('validate');
        $command->setValidator($v);
        $command->set(AbstractCommand::DISABLE_VALIDATION, true);
        $command->prepare();
    }

    public function testValidatorDoesNotUpdateNonDefaultValues()
    {
        $command = new MockCommand(array('test' => 123, 'foo' => 'bar'));
        $command->setClient(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client());
        $command->prepare();
        $this->assertEquals(123, $command->get('test'));
        $this->assertEquals('bar', $command->get('foo'));
    }

    public function testValidatorUpdatesDefaultValues()
    {
        $command = new MockCommand();
        $command->setClient(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client());
        $command->prepare();
        $this->assertEquals(123, $command->get('test'));
        $this->assertEquals('abc', $command->get('_internal'));
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ValidationException
     * @expectedExceptionMessage [Foo] Baz
     */
    public function testValidatesCommandBeforeSending()
    {
        $command = new MockCommand();
        $command->setClient(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client());
        $v = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\SchemaValidator')
            ->setMethods(array('validate', 'getErrors'))
            ->getMock();
        $v->expects($this->any())->method('validate')->will($this->returnValue(false));
        $v->expects($this->any())->method('getErrors')->will($this->returnValue(array('[Foo] Baz', '[Bar] Boo')));
        $command->setValidator($v);
        $command->prepare();
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ValidationException
     * @expectedExceptionMessage Validation errors: [abc] must be of type string
     */
    public function testValidatesAdditionalParameters()
    {
        $description = ServiceDescription::factory(array(
            'operations' => array(
                'foo' => array(
                    'parameters' => array(
                        'baz' => array('type' => 'integer')
                    ),
                    'additionalParameters' => array(
                        'type' => 'string'
                    )
                )
            )
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($description);
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('foo', array(
            'abc'             => false,
            'command.headers' => array('foo' => 'bar')
        ));
        $command->prepare();
    }

    public function testCanChangeResponseBody()
    {
        $body = EntityBody::factory();
        $command = new MockCommand();
        $command->setClient(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client());
        $command->set(AbstractCommand::RESPONSE_BODY, $body);
        $request = $command->prepare();
        $this->assertSame($body, $this->readAttribute($request, 'responseBody'));
    }
}
