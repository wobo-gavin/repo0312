<?php
/**
 * @pakage /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\FilterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilter;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ test type="class:/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bool_1 default="true" type="boolean"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bool_2 default="false"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ float type="float"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ int type="integer"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ date type="date"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ timestamp type="timestamp"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ string type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ username required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ dynamic default="{{username}}_{{ string }}_{{ does_not_exist }}"
 */
class InspectorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase implements FilterInterface
{
    public static $val = false;
    public static $args = array();

    public function process($command)
    {
        self::$val = true;

        return true;
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector::validateClass
     * @expectedException InvalidArgumentException
     */
    public function testValidatesRequiredArgs()
    {
        Inspector::getInstance()->validateClass(__CLASS__, new Collection());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector::prepareConfig
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
        } catch (\InvalidArgumentException $e) {
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector::validateClass
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector::validate
     * @expectedException InvalidArgumentException
     */
    public function testValidatesTypeHints()
    {
        Inspector::getInstance()->validateClass(__CLASS__, new Collection(array(
            'test' => 'uh oh',
            'username' => 'test'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector::validateClass
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector::validateConfig
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector::validate
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector
     */
    public function testInspectsClassArgs()
    {
        $doc = <<<EOT
/**
 * Client for interacting with the Unfuddle webservice
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ username required="true" doc="API username" type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ password required="true" doc="API password" type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ subdomain required="true" doc="Unfuddle project subdomain" type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ api_version required="true" default="v1" doc="API version" type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ protocol required="true" default="https" doc="HTTP protocol (http or https)" type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ base_url required="true" default="{{ protocol }}://{{ subdomain }}.unfuddle.com/api/{{ api_version }}/" doc="Unfuddle API base URL" type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ class type="class"
 */
EOT;

        $params = Inspector::getInstance()->parseDocBlock($doc);

        $this->assertEquals(array(
            'required' => 'true',
            'doc' => 'API username',
            'type' => 'string'
        ), $params['username']);

        $this->assertEquals(array(
            'required' => 'true',
            'default' => 'v1',
            'doc' => 'API version',
            'type' => 'string'
        ), $params['api_version']);

        $this->assertEquals(array(
            'required' => 'true',
            'default' => 'https',
            'doc' => 'HTTP protocol (http or https)',
            'type' => 'string'
        ), $params['protocol']);

        $this->assertEquals(array(
            'required' => 'true',
            'default' => '{{ protocol }}://{{ subdomain }}.unfuddle.com/api/{{ api_version }}/',
            'doc' => 'Unfuddle API base URL',
            'type' => 'string'
        ), $params['base_url']);

        $this->assertEquals(array(
            'type' => 'class'
        ), $params['class']);

        $config = new Collection(array(
            'username' => 'test',
            'password' => 'pass',
            'subdomain' => 'sub'
        ));

        Inspector::getInstance()->validateConfig($params, $config);

        // make sure the configs were injected
        $this->assertEquals('https://sub.unfuddle.com/api/v1/', $config->get('base_url'));

        try {
            Inspector::getInstance()->validateConfig($params, new Collection(array(
                'base_url' => '',
                'username' => '',
                'password' => '',
                'class' => '123'
            )));
            $this->fail('Expected exception not thrown when params are invalid');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals("Validation errors: Requires that the username argument be supplied.  (API username).
Requires that the password argument be supplied.  (API password).
Requires that the subdomain argument be supplied.  (Unfuddle project subdomain).
The supplied value is not an instance of stdClass: <string:123> supplied", $e->getMessage());
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector::registerFilter
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector::getRegisteredFilters
     */
    public function testRegistersCustomFilters()
    {
        $this->assertFalse(self::$val);

        $filter = new MockFilter();

        // Register a filter with no default argument
        Inspector::getInstance()->registerFilter('mock', __CLASS__);
        // Use a default argument
        Inspector::getInstance()->registerFilter('mock_2', $filter, 'arg');

        $validating = new Collection(array(
            'data' => 'false',
            'test' => 'aaa'
        ));

        Inspector::getInstance()->validateConfig(array(
            'data' => array(
                'type' => 'mock'
            ),
            'test' => array(
                'type' => 'mock_2'
            )
        ), $validating);

        $this->assertTrue(self::$val);

        $this->assertArrayHasKey('mock', Inspector::getInstance()->getRegisteredFilters());
        $this->assertArrayHasKey('mock_2', Inspector::getInstance()->getRegisteredFilters());

        $this->assertEquals(array(
            'aaa',
        ), $filter->commands);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector
     * @expectedException InvalidArgumentException
     */
    public function testChecksFilterValidity()
    {
        Inspector::getInstance()->validateConfig(array(
            'data' => array(
                'type' => 'invalid'
            )
        ), new Collection(array(
            'data' => 'false'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector
     */
    public function testValidatesArgs()
    {
        $config = new Collection(array(
            'data' => 123,
            'min' => 'a',
            'max' => 'aaa'
        ));

        $result = Inspector::getInstance()->validateConfig(array(
            'data' => array(
                'type' => 'string'
            ),
            'min' => array(
                'type' => 'string',
                'min_length' => 2
            ),
            'max' => array(
                'type' => 'string',
                'max_length' => 2
            )
        ), $config, false);

        $this->assertEquals("The supplied value is not a string: integer supplied
Requires that the min argument be >= 2 characters.
Requires that the max argument be <= 2 characters.", implode("\n", $result));
    }
}