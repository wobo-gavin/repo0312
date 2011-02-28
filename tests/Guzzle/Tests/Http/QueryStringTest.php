<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class QueryStringTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * The query string object to test
     *
     * @var \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString
     */
    protected $q;

    public function setup()
    {
        $this->q = new QueryString();
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::getFieldSeparator
     */
    public function testGetFieldSeparator()
    {
        $this->assertEquals('&', $this->q->getFieldSeparator());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::getPrefix
     */
    public function testGetPrefix()
    {
        $this->assertEquals('?', $this->q->getPrefix());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::getValueSeparator
     */
    public function testGetValueSeparator()
    {
        $this->assertEquals('=', $this->q->getValueSeparator());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::isEncodingFields
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setEncodeFields
     */
    public function testIsEncodingFields()
    {
        $this->assertTrue($this->q->isEncodingFields());
        $this->assertEquals($this->q, $this->q->setEncodeFields(false));
        $this->assertFalse($this->q->isEncodingFields());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::isEncodingValues
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setEncodeValues
     */
    public function testIsEncodingValues()
    {
        $this->assertTrue($this->q->isEncodingValues());
        $this->assertEquals($this->q, $this->q->setEncodeValues(false));
        $this->assertFalse($this->q->isEncodingValues());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setFieldSeparator
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setFieldSeparator
     */
    public function testSetFieldSeparator()
    {
        $this->assertEquals($this->q, $this->q->setFieldSeparator('/'));
        $this->assertEquals('/', $this->q->getFieldSeparator());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setPrefix
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::getPrefix
     */
    public function testSetPrefix()
    {
        $this->assertEquals($this->q, $this->q->setPrefix(''));
        $this->assertEquals('', $this->q->getPrefix());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setValueSeparator
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::getValueSeparator
     */
    public function testSetValueSeparator()
    {
        $this->assertEquals($this->q, $this->q->setValueSeparator('/'));
        $this->assertEquals('/', $this->q->getValueSeparator());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::urlEncode
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::rawUrlEncode
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::encodeData
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::replace
     */
    public function testUrlEncode()
    {
        $params = array(
            'test' => 'value',
            'test 2' => 'this is a test?',
            'test3' => array('v1', 'v2', 'v3')
        );
        $encoded = array(
            'test' => 'value',
            rawurlencode('test 2') => rawurlencode('this is a test?'),
            'test3[0]' => 'v1',
            'test3[1]' => 'v2',
            'test3[2]' => 'v3'
        );
        $this->q->replace($params);
        $this->assertEquals($encoded, $this->q->urlEncode());

        // Disable field encoding
        $testData = array(
            'test 2' => 'this is a test'
        );
        $this->q->replace($testData);
        $this->q->setEncodeFields(false);
        $this->assertEquals(array(
            'test 2' => rawurlencode('this is a test')
        ), $this->q->urlEncode());

        // Disable encoding of both fields and values
        $this->q->setEncodeValues(false);
        $this->assertEquals($testData, $this->q->urlEncode());


        $this->assertEquals('one&two%3D', QueryString::rawurlencode('one&two=', array('&')));
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::__toString
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setEncodeFields
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::replace
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setAggregateFunction
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::encodeData
     */
    public function testToString()
    {
        // Check with no parameters
        $this->assertEquals('', $this->q->__toString());

        $params = array(
            'test' => 'value',
            'test 2' => 'this is a test?',
            'test3' => array(
                'v1',
                'v2',
                'v3'
            )
        );
        $this->q->replace($params);
        $this->assertEquals('?test=value&test%202=this%20is%20a%20test%3F&test3[0]=v1&test3[1]=v2&test3[2]=v3', $this->q->__toString());
        $this->q->setEncodeFields(false);
        $this->q->setEncodeValues(false);
        $this->assertEquals('?test=value&test 2=this is a test?&test3[0]=v1&test3[1]=v2&test3[2]=v3', $this->q->__toString());

        // Use an alternative aggregator
        $this->q->setAggregateFunction(array($this->q, 'aggregateUsingComma'));
        $this->assertEquals('?test=value&test 2=this is a test?&test3=v1,v2,v3', $this->q->__toString());
    }
}