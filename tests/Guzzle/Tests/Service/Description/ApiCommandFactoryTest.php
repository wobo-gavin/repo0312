<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Description;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommandFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand;

class ApiCommandFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommandFactory
     */
    public function testBuildsCommandsUsingApiCommand()
    {
        $apiCommand = new ApiCommand(array(
            'name' => 'foo',
            'method' => 'GET',
            'path' => '/',
            'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\DynamicCommand'
        ));

        $factory = new ApiCommandFactory();

        $command = $factory->createCommand($apiCommand, array(
            'param' => 'value'
        ));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\DynamicCommand', $command);
        $this->assertEquals('value', $command->get('param'));
    }
}