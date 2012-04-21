<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\ConcreteClassFactory;

class ConcreteClassFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testProvider()
    {
        return array(
            array('foo', null, '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\'),
            array('mock_command', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\'),
            array('other_command', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\'),
            array('sub.sub', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\Sub\Sub', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\'),
            array('sub.sub', null, '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Foo\\'),
            array('foo', null, null),
            array('mock_command', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand', null),
            array('other_command', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand', null),
            array('sub.sub', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\Sub\Sub', null)
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\ConcreteClassFactory
     * @dataProvider testProvider
     */
    public function testCreatesConcreteCommands($key, $result, $prefix)
    {
        if (!$prefix) {
            $/* Replaced /* Replaced /* Replaced client */ */ */ = new MockClient();
        } else {
            $/* Replaced /* Replaced /* Replaced client */ */ */ = new MockClient('', array(
                'command.prefix' => $prefix
            ));
        }

        $factory = new ConcreteClassFactory($/* Replaced /* Replaced /* Replaced client */ */ */);

        if (is_null($result)) {
            $this->assertNull($factory->factory($key));
        } else {
            $this->assertInstanceof($result, $factory->factory($key));
        }
    }
}
