<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\AliasFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\MapFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\CompositeFactory;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\AliasFactory
 */
class AliasFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    private $factory;
    private $/* Replaced /* Replaced /* Replaced client */ */ */;

    public function setup()
    {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();

        $map = new MapFactory(array(
            'test'  => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand',
            'test1' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand'
        ));

        $this->factory = new AliasFactory($this->/* Replaced /* Replaced /* Replaced client */ */ */, array(
            'foo'      => 'test',
            'bar'      => 'sub',
            'sub'      => 'test1',
            'krull'    => 'test3',
            'krull_2'  => 'krull',
            'sub_2'    => 'bar',
            'bad_link' => 'jarjar'
        ));

        $map2 = new MapFactory(array(
            'test3'  => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\Sub\Sub'
        ));

        $this->/* Replaced /* Replaced /* Replaced client */ */ */->setCommandFactory(new CompositeFactory(array($map, $this->factory, $map2)));
    }

    public function aliasProvider()
    {
        return array(
            array('foo', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand', false),
            array('bar', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand', false),
            array('sub', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand', false),
            array('sub_2', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand', false),
            array('krull', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\Sub\Sub', false),
            array('krull_2', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\Sub\Sub', false),
            array('missing', null, true),
            array('bad_link', null, true)
        );
    }

    /**
     * @dataProvider aliasProvider
     */
    public function testAliasesCommands($key, $result, $exception)
    {
        try {
            $command = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getCommand($key);
            if (is_null($result)) {
                $this->assertNull($command);
            } else {
                $this->assertInstanceof($result, $command);
            }
        } catch (\Exception $e) {
            if (!$exception) {
                $this->fail('Got exception when it was not expected');
            }
        }
    }
}
