<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\MultiTransferException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\CommandTransferException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\CommandTransferException
 */
class CommandTransferExceptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testStoresCommands()
    {
        $c1 = new MockCommand();
        $c2 = new MockCommand();
        $e = new CommandTransferException('Test');
        $e->addSuccessfulCommand($c1)->addFailedCommand($c2);
        $this->assertSame(array($c1), $e->getSuccessfulCommands());
        $this->assertSame(array($c2), $e->getFailedCommands());
        $this->assertSame(array($c1, $c2), $e->getAllCommands());
    }

    public function testConvertsMultiExceptionIntoCommandTransfer()
    {
        $r1 = new Request('GET', 'http://foo.com');
        $r2 = new Request('GET', 'http://foobaz.com');
        $e = new MultiTransferException('Test', 123);
        $e->addSuccessfulRequest($r1)->addFailedRequest($r2);
        $ce = CommandTransferException::fromMultiTransferException($e);

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\CommandTransferException', $ce);
        $this->assertEquals('Test', $ce->getMessage());
        $this->assertEquals(123, $ce->getCode());
        $this->assertSame(array($r1), $ce->getSuccessfulRequests());
        $this->assertSame(array($r2), $ce->getFailedRequests());
    }
}
