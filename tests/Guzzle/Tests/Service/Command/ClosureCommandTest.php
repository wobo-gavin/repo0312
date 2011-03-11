<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand
     */
    public function testCanSetCanBatch()
    {
        $c = new ClosureCommand(array(
            'closure' => function() {},
            'closure_api' => true
        ));

        $this->assertTrue($c->canBatch());
        $this->assertSame($c, $c->setCanBatch(false));
        $this->assertFalse($c->canBatch());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand::prepare
     */
    public function testExecutesClosure()
    {
        $c = new ClosureCommand(array(
            'closure' => function($command, $api) {
                $command->set('testing', '123');
                $request = RequestFactory::get('http://www.test.com/');
                return $request;
            },
            'closure_api' => true
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('mock');
        $c->prepare($/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertEquals('123', $c->get('testing'));
        $this->assertEquals('http://www.test.com/', $c->getRequest()->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ClosureCommand
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException
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

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('mock');
        $c->prepare($/* Replaced /* Replaced /* Replaced client */ */ */);
    }
}