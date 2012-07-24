<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;

class QueryStringTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * The query string object to test
     *
     * @var /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString
     */
    protected $q;

    public function setup()
    {
        $this->q = new QueryString();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::getFieldSeparator
     */
    public function testGetFieldSeparator()
    {
        $this->assertEquals('&', $this->q->getFieldSeparator());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::getPrefix
     */
    public function testGetPrefix()
    {
        $this->assertEquals('?', $this->q->getPrefix());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::getValueSeparator
     */
    public function testGetValueSeparator()
    {
        $this->assertEquals('=', $this->q->getValueSeparator());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::isUrlEncoding
     */
    public function testIsUrlEncoding()
    {
        $this->assertTrue($this->q->isUrlEncoding());
        $this->assertSame($this->q, $this->q->useUrlEncoding(false));
        $this->assertFalse($this->q->isUrlEncoding());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setFieldSeparator
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setFieldSeparator
     */
    public function testSetFieldSeparator()
    {
        $this->assertEquals($this->q, $this->q->setFieldSeparator('/'));
        $this->assertEquals('/', $this->q->getFieldSeparator());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setPrefix
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::getPrefix
     */
    public function testSetPrefix()
    {
        $this->assertEquals($this->q, $this->q->setPrefix(''));
        $this->assertEquals('', $this->q->getPrefix());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setValueSeparator
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::getValueSeparator
     */
    public function testSetValueSeparator()
    {
        $this->assertEquals($this->q, $this->q->setValueSeparator('/'));
        $this->assertEquals('/', $this->q->getValueSeparator());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::urlEncode
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::encodeData
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::replace
     */
    public function testUrlEncode()
    {
        $params = array(
            'test'   => 'value',
            'test 2' => 'this is a test?',
            'test3'  => array('v1', 'v2', 'v3'),
            'ሴ'      => 'bar'
        );
        $encoded = array(
            'test'         => 'value',
            'test%202'     => rawurlencode('this is a test?'),
            'test3%5B0%5D' => 'v1',
            'test3%5B1%5D' => 'v2',
            'test3%5B2%5D' => 'v3',
            '%E1%88%B4'    => 'bar'
        );
        $this->q->replace($params);
        $this->assertEquals($encoded, $this->q->urlEncode());

        // Disable encoding
        $testData = array('test 2' => 'this is a test');
        $this->q->replace($testData);
        $this->q->useUrlEncoding(false);
        $this->assertEquals($testData, $this->q->urlEncode());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::__toString
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::useUrlEncoding
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::replace
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::setAggregateFunction
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::urlEncode
     */
    public function testToString()
    {
        // Check with no parameters
        $this->assertEquals('', $this->q->__toString());

        $params = array(
            'test'   => 'value',
            'test 2' => 'this is a test?',
            'test3'  => array('v1', 'v2', 'v3'),
            'test4'  => null,
        );
        $this->q->replace($params);
        $this->assertEquals('?test=value&test%202=this%20is%20a%20test%3F&test3%5B0%5D=v1&test3%5B1%5D=v2&test3%5B2%5D=v3&test4=', $this->q->__toString());
        $this->q->useUrlEncoding(false);
        $this->assertEquals('?test=value&test 2=this is a test?&test3[0]=v1&test3[1]=v2&test3[2]=v3&test4=', $this->q->__toString());

        // Use an alternative aggregator
        $this->q->setAggregateFunction(array($this->q, 'aggregateUsingComma'));
        $this->assertEquals('?test=value&test 2=this is a test?&test3=v1,v2,v3&test4=', $this->q->__toString());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::__toString
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::aggregateUsingDuplicates
     */
    public function testAllowsMultipleValuesPerKey()
    {
        $q = new QueryString();
        $q->add('facet', 'size');
        $q->add('facet', 'width');
        $q->add('facet.field', 'foo');
        // Use the duplicate aggregator
        $q->setAggregateFunction(array($this->q, 'aggregateUsingDuplicates'));
        $this->assertEquals('?facet=size&facet=width&facet.field=foo', $q->__toString());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::__toString
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::encodeData
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::aggregateUsingPhp
     */
    public function testAllowsNestedQueryData()
    {
        $this->q->replace(array(
            'test' => 'value',
            't' => array(
                'v1' => 'a',
                'v2' => 'b',
                'v3' => array(
                    'v4' => 'c',
                    'v5' => 'd',
                )
            )
        ));

        $this->q->useUrlEncoding(false);
        $this->assertEquals('?test=value&t[v1]=a&t[v2]=b&t[v3][v4]=c&t[v3][v5]=d', $this->q->__toString());
    }

    public function parseQueryProvider()
    {
        return array(
            // Ensure that multiple query string values are allowed per value
            array('q=a&q=b', array(
                'q' => array('a', 'b')
            )),
            // Ensure that PHP array style query string values are parsed
            array('q[]=a&q[]=b', array(
                'q' => array('a', 'b')
            )),
            // Ensure that decimals are allowed in query strings
            array('q.a=a&q.b=b', array(
                'q.a' => 'a',
                'q.b' => 'b'
            )),
            // Ensure that query string values are percent decoded
            array('q%20a=a%20b', array(
                'q a' => 'a b'
            )),
            // Ensure that values can be set without have a value
            array('q', array(
                'q' => null
            )),
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::fromString
     * @dataProvider parseQueryProvider
     */
    public function testParsesQueryStrings($query, $data)
    {
        $query = QueryString::fromString($query);
        $this->assertEquals($data, $query->getAll());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::fromString
     */
    public function testProperlyDealsWithDuplicateQueryStringValues()
    {
        $query = QueryString::fromString('foo=a&foo=b&?µ=c');
        $this->assertEquals(array('a', 'b'), $query->get('foo'));
        $this->assertEquals('c', $query->get('?µ'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::__toString
     */
    public function testAllowsBlankQueryStringValues()
    {
        $query = QueryString::fromString('foo');
        $this->assertEquals('?foo=', (string) $query);
        $query->set('foo', QueryString::BLANK);
        $this->assertEquals('?foo', (string) $query);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::fromString
     */
    public function testFromStringIgnoresQuestionMark()
    {
        $query = QueryString::fromString('?foo=baz&bar=boo');
        $this->assertEquals('?foo=baz&bar=boo', (string) $query);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::fromString
     */
    public function testConvertsPlusSymbolsToSpaces()
    {
        $query = QueryString::fromString('var=foo+bar');
        $this->assertEquals('foo bar', $query->get('var'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::__toString
     */
    public function testAllowsZeroValues()
    {
        $query = new QueryString(array(
            'foo' => 0,
            'baz' => '0',
            'bar' => null,
            'boo' => false
        ));
        $this->assertEquals('?foo=0&baz=0&bar=&boo=', (string) $query);
    }
}
