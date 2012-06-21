<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilderAbstractFactory;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilderAbstractFactory
 */
class ServiceBuilderAbstractFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $jsonFile;
    protected $xmlFile;

    public function setup()
    {
        $this->xmlFile = __DIR__ . '/../../TestData/services/new_style.xml';
        $this->jsonFile = __DIR__ . '/../../TestData/services/json1.json';
    }

    public function testFactoryDelegatesToConcreteFactories()
    {
        $factory = new ServiceBuilderAbstractFactory();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder', $factory->build($this->xmlFile));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder', $factory->build($this->jsonFile));

        $xml = new \SimpleXMLElement(file_get_contents($this->xmlFile));
        $xml->includes = null;
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder', $factory->build($xml));
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceBuilderException
     * @expectedExceptionMessage Unable to determine which factory to use based on the file extension of jarJarBinks. Valid file extensions are: .js, .json, .xml
     */
    public function testFactoryEnsuresItCanHandleTheTypeOfFileOrArray()
    {
        $factory = new ServiceBuilderAbstractFactory();
        $factory->build('jarJarBinks');
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceBuilderException
     * @expectedExceptionMessage Must pass a file name, array, or SimpleXMLElement
     */
    public function testThrowsExceptionWhenUnknownTypeIsPassed()
    {
        $factory = new ServiceBuilderAbstractFactory();
        $factory->build(new \stdClass());
    }
}
