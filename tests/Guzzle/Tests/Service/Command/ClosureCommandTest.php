<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

class ClosureCommandTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage A closure must be passed in the parameters array
     */
    public function testConstructorValidatesClosure()
    {
        $c = new ClosureCommand();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage A closure_api value must be passed in the parameters array
     */
    public function testConstructorValidatesClosureApi()
    {
        $c = new ClosureCommand(array(
            'closure' => function() {}
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand::prepare
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand::build
     */
    public function testExecutesClosure()
    {
        $c = new ClosureCommand(array(
            'closure' => function($command, $api) {
                $command->set('testing', '123');
                $request = RequestFactory::create('GET', 'http://www.test.com/');
                return $request;
            },
            'closure_api' => true
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->get('mock');
        $c->setClient($/* Replaced /* Replaced /* Replaced client */ */ */)->prepare();
        $this->assertEquals('123', $c->get('testing'));
        $this->assertEquals('http://www.test.com/', $c->getRequest()->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand
     * @expectedException UnexpectedValueException
     * @expectedExceptionMessage Closure command did not return a RequestInterface object
     */
    public function testMustReturnRequest()
    {
        $c = new ClosureCommand(array(
            'closure' => function($command, $api) {
                return false;
            },
            'closure_api' => true
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->get('mock');
        $c->setClient($/* Replaced /* Replaced /* Replaced client */ */ */)->prepare();
    }
}