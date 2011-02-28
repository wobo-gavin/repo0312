<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inflector;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class InflectorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inflector::snake
     */
    public function testSnake()
    {
        $this->assertEquals('camel_case', Inflector::snake('camelCase'));
        $this->assertEquals('camel_case', Inflector::snake('CamelCase'));
        $this->assertEquals('camel_case_words', Inflector::snake('CamelCaseWords'));
        $this->assertEquals('camel_case_words', Inflector::snake('CamelCase_words'));
        $this->assertEquals('test', Inflector::snake('test'));
        $this->assertEquals('test', Inflector::snake('test'));
        $this->assertEquals('expect100_continue', Inflector::snake('Expect100Continue'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inflector::camel
     */
    public function testCamel()
    {
        $this->assertEquals('camelCase', Inflector::camel('camel_case'));
        $this->assertEquals('camelCaseWords', Inflector::camel('camel_case_words'));
        $this->assertEquals('test', Inflector::camel('test'));
        $this->assertEquals('Expect100Continue', ucfirst(Inflector::camel('expect100_continue')));

        // Get from cache
        $this->assertEquals('test', Inflector::camel('test', false));
    }
}