<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\MapFactory;

class MapFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function mapProvider()
    {
        return array(
            array('foo', null),
            array('test', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand'),
            array('test1', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand')
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\MapFactory
     * @dataProvider mapProvider
     */
    public function testCreatesCommandsUsingMappings($key, $result)
    {
        $factory = new MapFactory(array(
            'test'  => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand',
            'test1' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand'
        ));

        if (is_null($result)) {
            $this->assertNull($factory->factory($key));
        } else {
            $this->assertInstanceof($result, $factory->factory($key));
        }
    }
}
