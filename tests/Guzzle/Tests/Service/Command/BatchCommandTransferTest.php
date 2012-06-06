<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand as Mc;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\BatchCommandTransfer;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\BatchCommandTransfer
 */
class BatchCommandTransferTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testCreatesBatchesBasedOnClient()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */1 = new Client('http://www.example.com');
        $/* Replaced /* Replaced /* Replaced client */ */ */2 = new Client('http://www.example.com');

        $commands = array(new Mc(), new Mc(), new Mc(), new Mc(), new Mc());

        $queue = new \SplQueue();
        foreach ($commands as $i => $command) {
            if ($i % 2) {
                $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */1);
            } else {
                $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */2);
            }
            $queue[] = $command;
        }

        $batch = new BatchCommandTransfer(2);
        $this->assertEquals(array(
            array($commands[0], $commands[2]),
            array($commands[4]),
            array($commands[1], $commands[3])
        ), $batch->createBatches($queue));
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testEnsuresAllItemsAreCommands()
    {
        $queue = new \SplQueue();
        $queue[] = 'foo';
        $batch = new BatchCommandTransfer(2);
        $batch->createBatches($queue);
    }

    public function testTransfersBatches()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client')
            ->setMethods(array('send'))
            ->getMock();
        $/* Replaced /* Replaced /* Replaced client */ */ */->expects($this->once())
            ->method('send');
        $command = new Mc();
        $command->setClient($/* Replaced /* Replaced /* Replaced client */ */ */);
        $batch = new BatchCommandTransfer(2);
        $batch->transfer(array($command));
    }

    public function testDoesNotTransfersEmptyBatches()
    {
        $batch = new BatchCommandTransfer(2);
        $batch->transfer(array());
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\InconsistentClientTransferException
     */
    public function testEnsuresAllCommandsUseTheSameClient()
    {
        $batch = new BatchCommandTransfer(2);
        $/* Replaced /* Replaced /* Replaced client */ */ */1 = new Client('http://www.example.com');
        $/* Replaced /* Replaced /* Replaced client */ */ */2 = new Client('http://www.example.com');
        $command1 = new Mc();
        $command1->setClient($/* Replaced /* Replaced /* Replaced client */ */ */1);
        $command2 = new Mc();
        $command2->setClient($/* Replaced /* Replaced /* Replaced client */ */ */2);
        $batch->transfer(array($command1, $command2));
    }
}
