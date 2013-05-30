<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand
 */
class ClosureCommandTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage A closure must be passed in the parameters array
     */
    public function testConstructorValidatesClosure()
    {
        $c = new ClosureCommand();
    }

    public function testExecutesClosure()
    {
        $c = new ClosureCommand(array(
            'closure' => function($command, $api) {
                $command->set('testing', '123');
                $request = RequestFactory::getInstance()->create('GET', 'http://www.test.com/');
                return $request;
            }
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->get('mock');
        $c->setClient($/* Replaced /* Replaced /* Replaced client */ */ */)->prepare();
        $this->assertEquals('123', $c->get('testing'));
        $this->assertEquals('http://www.test.com/', $c->getRequest()->getUrl());
    }

    /**
     * @expectedException UnexpectedValueException
     * @expectedExceptionMessage Closure command did not return a RequestInterface object
     */
    public function testMustReturnRequest()
    {
        $c = new ClosureCommand(array(
            'closure' => function($command, $api) {
                return false;
            }
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->get('mock');
        $c->setClient($/* Replaced /* Replaced /* Replaced client */ */ */)->prepare();
    }
}
