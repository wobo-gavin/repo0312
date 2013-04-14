<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\ServiceDescriptionFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Inflection\Inflector;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory\ServiceDescriptionFactory
 */
class ServiceDescriptionFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testProvider()
    {
        return array(
            array('foo', null),
            array('jar_jar', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand'),
            array('binks', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand')
        );
    }

    /**
     * @dataProvider testProvider
     */
    public function testCreatesCommandsUsingServiceDescriptions($key, $result)
    {
        $d = $this->getDescription();

        $factory = new ServiceDescriptionFactory($d);
        $this->assertSame($d, $factory->getServiceDescription());

        if (is_null($result)) {
            $this->assertNull($factory->factory($key));
        } else {
            $this->assertInstanceof($result, $factory->factory($key));
        }
    }

    public function testUsesUcFirstIfNoExactMatch()
    {
        $d = $this->getDescription();
        $factory = new ServiceDescriptionFactory($d, new Inflector());
        $this->assertInstanceof('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand', $factory->factory('Test'));
        $this->assertInstanceof('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand', $factory->factory('test'));
    }

    public function testUsesInflectionIfNoExactMatch()
    {
        $d = $this->getDescription();
        $factory = new ServiceDescriptionFactory($d, new Inflector());
        $this->assertInstanceof('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand', $factory->factory('Binks'));
        $this->assertInstanceof('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand', $factory->factory('binks'));
        $this->assertInstanceof('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand', $factory->factory('JarJar'));
        $this->assertInstanceof('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand', $factory->factory('jar_jar'));
    }

    protected function getDescription()
    {
        return ServiceDescription::factory(array(
            'operations' => array(
                'jar_jar' => array('class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand'),
                'binks' => array('class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand'),
                'Test' => array('class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\OtherCommand')
            )
        ));
    }
}
