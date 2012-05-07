<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiParam;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ValidationException;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ test type="type:object"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bool_1 default="true" type="boolean"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bool_2 default="false"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ float type="float"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ int type="integer"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ date type="date"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ timestamp type="time"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ string type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ username required="true" filters="strtolower"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ dynamic default="{{username}}_{{ string }}_{{ does_not_exist }}"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ test_function type="string" filters="/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\InspectorTest::strtoupper"
 */
class InspectorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public static function strtoupper($string)
    {
        return strtoupper($string);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::validateClass
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ValidationException
     */
    public function testValidatesRequiredArgs()
    {
        Inspector::getInstance()->validateClass(__CLASS__, new Collection());
    }

    /**
     * @cover /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::__constructor
     */
    public function testRegistersDefaultFilters()
    {
        $inspector = new Inspector();
        $this->assertNotEmpty($inspector->getRegisteredConstraints());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::prepareConfig
     */
    public function testPreparesConfig()
    {
        $c = Inspector::prepareConfig(array(
            'a' => '123',
            'base_url' => 'http://www.test.com/'
        ), array(
            'a' => 'xyz',
            'b' => 'lol'
        ), array('a'));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection', $c);
        $this->assertEquals(array(
            'a' => '123',
            'b' => 'lol',
            'base_url' => 'http://www.test.com/'
        ), $c->getAll());

        try {
            $c = Inspector::prepareConfig(null, null, array('a'));
            $this->fail('Exception not throw when missing config');
        } catch (ValidationException $e) {
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector
     */
    public function testAddsDefaultAndInjectsConfigs()
    {
        $col = new Collection(array(
            'username' => 'user',
            'string' => 'test',
            'float' => 1.23
        ));

        Inspector::getInstance()->validateClass(__CLASS__, $col);
        $this->assertEquals(false, $col->get('bool_2'));
        $this->assertEquals('user_test_', $col->get('dynamic'));
        $this->assertEquals(1.23, $col->get('float'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::validateClass
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ValidationException
     */
    public function testValidatesTypeHints()
    {
        Inspector::getInstance()->validateClass(__CLASS__, new Collection(array(
            'test' => 'uh oh',
            'username' => 'test'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::validateClass
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::validateConfig
     */
    public function testConvertsBooleanDefaults()
    {
        $c = new Collection(array(
            'test' => $this,
            'username' => 'test'
        ));

        Inspector::getInstance()->validateClass(__CLASS__, $c);

        $this->assertTrue($c->get('bool_1'));
        $this->assertFalse($c->get('bool_2'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector
     */
    public function testInspectsClassArgs()
    {
        $doc = <<<EOT
/**
 * Client for interacting with the Unfuddle webservice
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ username required="true" doc="API username" type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ password required="true" doc="API password" type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ subdomain required="true" doc="Unfuddle project subdomain" type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ api_version required="true" default="v1" doc="API version" type="choice:'v1','v2',v3"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ protocol required="true" default="https" doc="HTTP protocol (http or https)" type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ base_url required="true" default="{{ protocol }}://{{ subdomain }}.unfuddle.com/api/{{ api_version }}/" doc="Unfuddle API base URL" type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ class type="type:object"
 */
EOT;

        $params = Inspector::getInstance()->parseDocBlock($doc);

        $this->assertEquals(array(
            'required' => 'true',
            'doc' => 'API username',
            'type' => 'string'
        ), array_filter($params['username']->toArray()));

        $this->assertEquals(array(
            'required' => 'true',
            'default' => 'v1',
            'doc' => 'API version',
            'type' => "choice:'v1','v2',v3"
        ), array_filter($params['api_version']->toArray()));

        $this->assertEquals(array(
            'required' => 'true',
            'default' => 'https',
            'doc' => 'HTTP protocol (http or https)',
            'type' => 'string'
        ), array_filter($params['protocol']->toArray()));

        $this->assertEquals(array(
            'required' => 'true',
            'default' => '{{ protocol }}://{{ subdomain }}.unfuddle.com/api/{{ api_version }}/',
            'doc' => 'Unfuddle API base URL',
            'type' => 'string'
        ), array_filter($params['base_url']->toArray()));

        $this->assertEquals(array(
            'type' => "type:object"
        ), array_filter($params['class']->toArray()));

        $config = new Collection(array(
            'username' => 'test',
            'password' => 'pass',
            'subdomain' => 'sub',
            'api_version' => 'v2'
        ));

        Inspector::getInstance()->validateConfig($params, $config);

        // make sure the configs were injected
        $this->assertEquals('https://sub.unfuddle.com/api/v2/', $config->get('base_url'));

        try {
            Inspector::getInstance()->validateConfig($params, new Collection(array(
                'base_url' => '',
                'username' => '',
                'password' => '',
                'class' => '123',
                'api_version' => 'v10'
            )));
            $this->fail('Expected exception not thrown when params are invalid');
        } catch (ValidationException $e) {

            $concat = $e->getMessage();
            $this->assertContains("Validation errors: Requires that the username argument be supplied.  (API username)", $concat);
            $this->assertContains("Requires that the password argument be supplied.  (API password)", $concat);
            $this->assertContains("Requires that the subdomain argument be supplied.  (Unfuddle project subdomain)", $concat);
            $this->assertContains("Value must be one of: v1, v2, v3", $concat);
            $this->assertContains("Value must be of type object", $concat);
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::registerConstraint
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::getConstraint
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::getRegisteredConstraints
     */
    public function testRegistersCustomConstraints()
    {
        $constraintClass = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Common\\Validation\\Ip';

        Inspector::getInstance()->registerConstraint('mock', $constraintClass);
        Inspector::getInstance()->registerConstraint('mock_2', $constraintClass, array(
           'version' => '4'
        ));

        $this->assertArrayHasKey('mock', Inspector::getInstance()->getRegisteredConstraints());
        $this->assertArrayHasKey('mock_2', Inspector::getInstance()->getRegisteredConstraints());

        $this->assertInstanceOf($constraintClass, Inspector::getInstance()->getConstraint('mock'));
        $this->assertInstanceOf($constraintClass, Inspector::getInstance()->getConstraint('mock_2'));

        $validating = new Collection(array(
            'data' => '192.168.16.121',
            'test' => '10.1.1.0'
        ));

        $this->assertTrue(Inspector::getInstance()->validateConfig(array(
            'data' => new ApiParam(array(
                'type' => 'mock',
                'name' => 'data'
            )),
            'test' => new ApiParam(array(
                'type' => 'mock_2',
                'name' => 'test'
            ))
        ), $validating, false));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector
     * @expectedException InvalidArgumentException
     */
    public function testChecksFilterValidity()
    {
        Inspector::getInstance()->validateConfig(array(
            'data' => new ApiParam(array(
                'type' => 'invalid'
            ))
        ), new Collection(array(
            'data' => 'false'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector
     */
    public function testValidatesArgs()
    {
        $config = new Collection(array(
            'data' => 123,
            'min' => 'a',
            'max' => 'aaa'
        ));

        $result = Inspector::getInstance()->validateConfig(array(
            'data' => new ApiParam(array(
                'type' => 'string'
            )),
            'min' => new ApiParam(array(
                'type' => 'string',
                'min_length' => 2
            )),
            'max' => new ApiParam(array(
                'type' => 'string',
                'max_length' => 2
            ))
        ), $config, false);

        $concat = implode("\n", $result);
        $this->assertContains("Value must be of type string", $concat);
        $this->assertContains("Requires that the min argument be >= 2 characters", $concat);
        $this->assertContains("Requires that the max argument be <= 2 characters", $concat);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::parseDocBlock
     */
    public function testVerifies/* Replaced /* Replaced /* Replaced Guzzle */ */ */Annotations()
    {
        $this->assertEquals(
            array(),
            Inspector::getInstance()->parseDocBlock('testing')
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::validateConfig
     */
    public function testRunsValuesThroughFilters()
    {
        $data = new Collection(array(
            'username' => 'TEST',
            'test_function'   => 'foo'
        ));
        Inspector::getInstance()->validateClass(__CLASS__, $data);
        $this->assertEquals('test', $data->get('username'));
        $this->assertEquals('FOO', $data->get('test_function'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::setTypeValidation
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::validateConfig
     */
    public function testTypeValidationCanBeDisabled()
    {
        $i = Inspector::getInstance();
        $i->setTypeValidation(false);

        // Ensure that the type is not validated
        $i->validateConfig(array(
            'data' => new ApiParam(array(
                'type' => 'string'
            ))
        ), new Collection(array(
            'data' => new \stdClass()
        )), true);

        $i->setTypeValidation(true);

        // Ensure that nothing is validated
        $i->validateConfig(array(
            'data' => new ApiParam(array(
                'type' => 'string'
            ))
        ), new Collection(array(
            'data' => new \stdClass()
        )), true, false);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector::validateConfig
     */
    public function testSkipsFurtherValidationIfNotSet()
    {
        $i = Inspector::getInstance();

        // Ensure that the type is not validated
        $this->assertEquals(true, $i->validateConfig(array(
            'data' => new ApiParam(array(
                'type' => 'string'
            ))
        ), new Collection(), true));
    }
}
