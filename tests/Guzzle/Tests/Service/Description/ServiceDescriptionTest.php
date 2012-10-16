<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Description;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription
 */
class ServiceDescriptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $serviceData;

    public function setup()
    {
        $this->serviceData = array(
            'test_command' => new Operation(array(
                'name'        => 'test_command',
                'description' => 'documentationForCommand',
                'httpMethod'  => 'DELETE',
                'class'       => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\MockCommand',
                'parameters'  => array(
                    'bucket'  => array('required' => true),
                    'key'     => array('required' => true)
                )
            ))
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescriptionLoader::build
     */
    public function testFactoryDelegatesToConcreteFactories()
    {
        $jsonFile = __DIR__ . '/../../TestData/test_service.json';
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription', ServiceDescription::factory($jsonFile));
    }

    public function testConstructor()
    {
        $service = new ServiceDescription(array('operations' => $this->serviceData));
        $this->assertEquals(1, count($service->getOperations()));
        $this->assertFalse($service->hasOperation('foobar'));
        $this->assertTrue($service->hasOperation('test_command'));
    }

    public function testIsSerializable()
    {
        $service = new ServiceDescription(array('operations' => $this->serviceData));
        $data = serialize($service);
        $d2 = unserialize($data);
        $this->assertEquals(serialize($service), serialize($d2));
    }

    public function testSerializesParameters()
    {
        $service = new ServiceDescription(array(
            'operations' => array(
                'foo' => new Operation(array('parameters' => array('foo' => array('type' => 'string'))))
            )
        ));
        $serialized = serialize($service);
        $this->assertContains('"parameters":{"foo":', $serialized);
        $service = unserialize($serialized);
        $this->assertTrue($service->getOperation('foo')->hasParam('foo'));
    }

    public function testAllowsForJsonBasedArrayParamsFunctionalTest()
    {
        $description = new ServiceDescription(array(
            'operations' => array(
                'test' => new Operation(array(
                    'httpMethod' => 'PUT',
                    'parameters' => array(
                        'data'   => array(
                            'required' => true,
                            'filters'  => 'json_encode',
                            'location' => 'body'
                        )
                    )
                ))
            )
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($description);
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('test', array(
            'data' => array(
                'foo' => 'bar'
            )
        ));

        $request = $command->prepare();
        $this->assertEquals('{"foo":"bar"}', (string) $request->getBody());
    }

    public function testContainsModels()
    {
        $d = new ServiceDescription(array(
            'operations' => array('foo' => array()),
            'models' => array(
                'Tag'    => array('type' => 'object'),
                'Person' => array('type' => 'object')
            )
        ));
        $this->assertTrue($d->hasModel('Tag'));
        $this->assertTrue($d->hasModel('Person'));
        $this->assertFalse($d->hasModel('Foo'));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter', $d->getModel('Tag'));
        $this->assertNull($d->getModel('Foo'));
        $this->assertContains('"models":{', serialize($d));
    }

    public function testCanAddModels()
    {
        $d = new ServiceDescription(array());
        $this->assertFalse($d->hasModel('Foo'));
        $d->addModel(new Parameter(array('name' => 'Foo')));
        $this->assertTrue($d->hasModel('Foo'));
    }

    public function testHasAttributes()
    {
        $d = new ServiceDescription(array(
            'operations'  => array(),
            'name'        => 'Name',
            'description' => 'Description',
            'apiVersion'  => '1.24'
        ));

        $this->assertEquals('Name', $d->getName());
        $this->assertEquals('Description', $d->getDescription());
        $this->assertEquals('1.24', $d->getApiVersion());

        $s = serialize($d);
        $this->assertContains('"name":"Name"', $s);
        $this->assertContains('"description":"Description"', $s);
        $this->assertContains('"apiVersion":"1.24"', $s);

        $d = unserialize($s);
        $this->assertEquals('Name', $d->getName());
        $this->assertEquals('Description', $d->getDescription());
        $this->assertEquals('1.24', $d->getApiVersion());
    }

    public function testPersistsCustomAttributes()
    {
        $data = array(
            'operations'  => array('foo' => array('class' => 'foo', 'parameters' => array())),
            'name'        => 'Name',
            'description' => 'Test',
            'apiVersion'  => '1.24',
            'auth'        => 'foo',
            'keyParam'    => 'bar'
        );
        $d = new ServiceDescription($data);
        $this->assertEquals('foo', $d->getData('auth'));
        $this->assertEquals('bar', $d->getData('keyParam'));
        // responseClass and responseType are added by default
        $data['operations']['foo']['responseClass'] = 'array';
        $data['operations']['foo']['responseType'] = 'primitive';
        $this->assertEquals($data, json_decode($d->serialize(), true));
    }

    public function testReturnsNullWhenRetrievingMissingOperation()
    {
        $s = new ServiceDescription(array());
        $this->assertNull($s->getOperation('foo'));
    }

    public function testCanAddOperations()
    {
        $s = new ServiceDescription(array());
        $this->assertFalse($s->hasOperation('Foo'));
        $s->addOperation(new Operation(array('name' => 'Foo')));
        $this->assertTrue($s->hasOperation('Foo'));
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testValidatesOperationTypes()
    {
        $s = new ServiceDescription(array(
            'operations' => array('foo' => new \stdClass())
        ));
    }

    public function testHasBaseUrl()
    {
        $description = new ServiceDescription(array('baseUrl' => 'http://foo.com'));
        $this->assertEquals('http://foo.com', $description->getBaseUrl());
        $description->setBaseUrl('http://foobar.com');
        $this->assertEquals('http://foobar.com', $description->getBaseUrl());
    }

    public function testCanUseBasePath()
    {
        $description = new ServiceDescription(array('basePath' => 'http://foo.com'));
        $this->assertEquals('http://foo.com', $description->getBaseUrl());
    }
}
