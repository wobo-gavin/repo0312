<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\DescriptionBuilder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\XmlDescriptionBuilder;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class XmlDescriptionBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\XmlDescriptionBuilder
     * @expectedException InvalidArgumentException
     */
    public function testXmlBuilderThrowsExceptionWhenFileIsNotFound()
    {
        $builder = new XmlDescriptionBuilder('file_not_found');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\XmlDescriptionBuilder
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription
     */
    public function testBuildsServiceUsingFile()
    {
        $builder = new XmlDescriptionBuilder(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'test_service.xml');
        $service = $builder->build();
        $this->assertEquals('Test Service', $service->getName());
        $this->assertEquals('Description', $service->getDescription());
        $this->assertEquals('http://www.test.com/', $service->getBaseUrl());
        $this->assertTrue($service->hasCommand('search'));
        $this->assertTrue($service->hasCommand('test'));
        $this->assertTrue($service->hasCommand('trends.location'));
        $this->assertTrue($service->hasCommand('geo.id'));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\ApiCommand', $service->getCommand('search'));
        $this->assertInternalType('array', $service->getCommands());
        $this->assertEquals(4, count($service->getCommands()));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Common\\NullObject', $service->getCommand('missing'));

        $command = $service->getCommand('test');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\ApiCommand', $command);
        $this->assertEquals('test', $command->getName());
        $this->assertFalse($command->canBatch());
        $this->assertInternalType('array', $command->getArgs());

        $this->assertEquals(array(
            'name' => 'bucket',
            'required' => true,
            'location' => 'path',
            'doc' => 'Bucket location'
        ), $command->getArg('bucket')->getAll());

        $this->assertEquals('DELETE', $command->getMethod());
        $this->assertEquals('2', $command->getMinArgs());
        $this->assertEquals('{{ bucket }}/{{ key }}{{ format }}', $command->getPath());
        $this->assertEquals('Documentation', $command->getDoc());

        $this->assertArrayHasKey('custom_filter', Inspector::getInstance()->getRegisteredFilters());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\XmlDescriptionBuilder
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription
     */
    public function testBuildsServiceUsingXml()
    {
        $xml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<service>
    <name>Test Service</name>
    <description>Description</description>
    <base_url>{{ protocol }}://www.test.com/</base_url>
    </* Replaced /* Replaced /* Replaced client */ */ */>/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Service.Client<//* Replaced /* Replaced /* Replaced client */ */ */>
    <types>
        <type name="slug" class="/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Common.InspectorFilter.Regex" default_args="/[0-9a-zA-z_\-]+/" />
    </types>
    <commands>
        <command name="geo.id" method="GET" auth_required="true" path="/geo/id/:place_id">
            <param name="place_id" type="string" required="true"/>
        </command>
    </commands>
</service>
EOT;
        
        $builder = new XmlDescriptionBuilder($xml);
        $service = $builder->build();
        $this->assertEquals('Test Service', $service->getName());
        $this->assertEquals('Description', $service->getDescription());
        $this->assertEquals('{{ protocol }}://www.test.com/', $service->getBaseUrl());
        $this->assertTrue($service->hasCommand('geo.id'));
        $this->assertTrue(is_array($service->getClientArgs()));
        $this->arrayHasKey('slug', Inspector::getInstance()->getRegisteredFilters());
    }
}