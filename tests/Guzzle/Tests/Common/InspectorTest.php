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
        $col = new Collection();
        Inspector::getInstance()->validateClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\UnfuddleClient', $col);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector
     */
    public function testAddsDefaultAndInjectsConfigs()
    {
        $col = new Collection(array(
            'username' => 'user',
            'password' => 'test',
            'subdomain' => 'test.{{ username }}'
        ));

        Inspector::getInstance()->validateClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\UnfuddleClient', $col);
        $this->assertEquals('https', $col->get('protocol'));
        $this->assertEquals('v1', $col->get('api_version'));
        $this->assertEquals('user', $col->get('username'));
        $this->assertEquals('test.user', $col->get('subdomain'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector::validateClass
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector::validate
     * @expectedException InvalidArgumentException
     */
    public function testValidatesTypeHints()
    {
        Inspector::getInstance()->validateClass(__CLASS__, new Collection(array(
            'test' => 'uh oh'
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
            'test' => $this
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

        $this->assertEquals('Client for interacting with the Unfuddle webservice', $params['doc']);

        $params = $params['args'];

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
        ), new Collection(array(
            'data' => 123,
            'min' => 'a',
            'max' => 'aaa',
            'test' => '{{ data }}{{ max }} whoo'
        )), false);

        $this->assertEquals("The supplied value is not a string: integer supplied
Requires that the min argument be >= 2 characters.
Requires that the max argument be <= 2 characters.", implode("\n", $result));
    }
}