<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Description;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescriptionAbstractFactory;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescriptionAbstractFactory
 */
class ServiceDescriptionAbstractFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testFactoryDelegatesToConcreteFactories()
    {
        $factory = new ServiceDescriptionAbstractFactory();
        $this->assertInstanceOf(
            '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription',
            $factory->build(__DIR__ . '/../../TestData/test_service.json')
        );
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\DescriptionBuilderException
     */
    public function testFactoryEnsuresItCanHandleTheTypeOfFileOrArray()
    {
        $factory = new ServiceDescriptionAbstractFactory();
        $factory->build('jarJarBinks');
    }
}
