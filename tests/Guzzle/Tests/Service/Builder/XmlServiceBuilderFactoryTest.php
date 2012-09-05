<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ArrayServiceBuilderFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\XmlServiceBuilderFactory;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\XmlServiceBuilderFactory
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ArrayServiceBuilderFactory
 */
class XmlServiceBuilderFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testBuildsServiceBuilders()
    {
        $xmlFactory = new XmlServiceBuilderFactory(new ArrayServiceBuilderFactory);
        $file = __DIR__ . '/../../TestData/services/new_style.xml';

        $builder = $xmlFactory->build($file);

        // Ensure that services were parsed
        $this->assertTrue(isset($builder['mock']));
        $this->assertTrue(isset($builder['abstract']));
        $this->assertTrue(isset($builder['foo']));
        $this->assertFalse(isset($builder['jimmy']));
    }

    public function testBuildsServiceBuildersUsingSimpleXmlElement()
    {
        $xmlFactory = new XmlServiceBuilderFactory(new ArrayServiceBuilderFactory);
        $file = __DIR__ . '/../../TestData/services/new_style.xml';
        $xml = new \SimpleXMLElement(file_get_contents($file));
        $xml->includes = null;
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder', $xmlFactory->build($xml));
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceBuilderException
     */
    public function testCannotExtendWhenUsingSimpleXMLElement()
    {
        $xmlFactory = new XmlServiceBuilderFactory(new ArrayServiceBuilderFactory());
        $file = __DIR__ . '/../../TestData/services/new_style.xml';
        $xml = new \SimpleXMLElement(file_get_contents($file));
        $xmlFactory->build($xml);
    }
}
