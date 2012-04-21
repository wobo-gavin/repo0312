<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\ServiceDescriptionFactory;

class ServiceDescriptionFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testProvider()
    {
        return array(
            array('foo', null),
            array('jarjar', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand'),
            array('binks', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand')
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\ServiceDescriptionFactory
     * @dataProvider testProvider
     */
    public function testCreatesCommandsUsingServiceDescriptions($key, $result)
    {
        $d = ServiceDescription::factory(array(
            'commands' => array(
                'jarjar' => array(
                    'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand'
                ),
                'binks' => array(
                    'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand'
                )
            )
        ));

        $factory = new ServiceDescriptionFactory($d);
        $this->assertSame($d, $factory->getServiceDescription());

        if (is_null($result)) {
            $this->assertNull($factory->factory($key));
        } else {
            $this->assertInstanceof($result, $factory->factory($key));
        }
    }
}
