<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Inflection;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Inflection\Inflector;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Inflection\Inflector
 */
class InflectorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testReturnsDefaultInstance()
    {
        $this->assertSame(Inflector::getDefault(), Inflector::getDefault());
    }

    public function testSnake()
    {
        $this->assertEquals('camel_case', Inflector::getDefault()->snake('camelCase'));
        $this->assertEquals('camel_case', Inflector::getDefault()->snake('CamelCase'));
        $this->assertEquals('camel_case_words', Inflector::getDefault()->snake('CamelCaseWords'));
        $this->assertEquals('camel_case_words', Inflector::getDefault()->snake('CamelCase_words'));
        $this->assertEquals('test', Inflector::getDefault()->snake('test'));
        $this->assertEquals('test', Inflector::getDefault()->snake('test'));
        $this->assertEquals('expect100_continue', Inflector::getDefault()->snake('Expect100Continue'));
    }

    public function testCamel()
    {
        $this->assertEquals('CamelCase', Inflector::getDefault()->camel('camel_case'));
        $this->assertEquals('CamelCaseWords', Inflector::getDefault()->camel('camel_case_words'));
        $this->assertEquals('Test', Inflector::getDefault()->camel('test'));
        $this->assertEquals('Expect100Continue', ucfirst(Inflector::getDefault()->camel('expect100_continue')));
        // Get from cache
        $this->assertEquals('Test', Inflector::getDefault()->camel('test', false));
    }
}
