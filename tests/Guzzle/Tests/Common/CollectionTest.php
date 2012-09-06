<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;

class CollectionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection
     */
    protected $coll;

    protected function setUp()
    {
        $this->coll = new Collection();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::__construct
     */
    public function testConstructorCanBeCalledWithNoParams()
    {
        $this->coll = new Collection();
        $p = $this->coll->getAll();
        $this->assertEmpty($p, '-> Collection must be empty when no data is passed');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::getAll
     */
    public function testConstructorCanBeCalledWithParams()
    {
        $testData = array(
            'test' => 'value',
            'test_2' => 'value2'
        );
        $this->coll = new Collection($testData);
        $this->assertEquals($this->coll->getAll(), $testData, '-> getAll() must return the data passed in the constructor');
    }

    /**
     * Test the IteratorAggregate implementation of theCollection object
     *
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::getIterator
     */
    public function testImplementsIteratorAggregate()
    {
        $this->coll->set('key', 'value');
        $this->assertInstanceOf('ArrayIterator', $this->coll->getIterator());
        $this->assertEquals(1, count($this->coll));
        $total = 0;
        foreach ($this->coll as $key => $value) {
            $this->assertEquals('key', $key);
            $this->assertEquals('value', $value);
            $total++;
        }
        $this->assertEquals(1, $total);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::add
     */
    public function testCanAddValuesToExistingKeysByUsingArray()
    {
        $this->coll->add('test', 'value1');
        $this->assertEquals($this->coll->getAll(), array('test' => 'value1'));
        $this->coll->add('test', 'value2');
        $this->assertEquals($this->coll->getAll(), array('test' => array('value1', 'value2')));
        $this->coll->add('test', 'value3');
        $this->assertEquals($this->coll->getAll(), array('test' => array('value1', 'value2', 'value3')));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::merge
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::getAll
     */
    public function testHandlesMergingInDisparateDataSources()
    {
        $params = array(
            'test' => 'value1',
            'test2' => 'value2',
            'test3' => array('value3', 'value4')
        );
        $this->coll->merge($params);
        $this->assertEquals($this->coll->getAll(), $params);

        // Pass an invalid value and expect the same unaltered object
        $this->assertEquals($this->coll->merge(false), $this->coll);

        // Pass the same object to itself
        $this->assertEquals($this->coll->merge($this->coll), $this->coll);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::clear
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::remove
     */
    public function testCanClearAllDataOrSpecificKeys()
    {
        $this->coll->merge(array(
            'test' => 'value1',
            'test2' => 'value2'
        ));

        // Clear a specific parameter by name
        $this->coll->remove('test');

        $this->assertEquals($this->coll->getAll(), array(
            'test2' => 'value2'
        ));

        // Clear all parameters
        $this->coll->clear();

        $this->assertEquals($this->coll->getAll(), array());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::get
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::getAll
     */
    public function testGetsValuesByKey()
    {
        $this->assertNull($this->coll->get('test'));
        $this->coll->add('test', 'value');
        $this->assertEquals('value', $this->coll->get('test'));
        $this->coll->set('test2', 'v2');
        $this->coll->set('test3', 'v3');
        $this->assertEquals(array(
            'test' => 'value',
            'test2' => 'v2'
        ), $this->coll->getAll(array('test', 'test2')));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::getKeys
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::remove
     */
    public function testProvidesKeys()
    {
        $this->assertEquals(array(), $this->coll->getKeys());
        $this->coll->merge(array(
            'test1' => 'value1',
            'test2' => 'value2'
        ));
        $this->assertEquals(array('test1', 'test2'), $this->coll->getKeys());
        // Returns the cached array previously returned
        $this->assertEquals(array('test1', 'test2'), $this->coll->getKeys());
        $this->coll->remove('test1');
        $this->assertEquals(array('test2'), $this->coll->getKeys());
        $this->coll->add('test3', 'value3');
        $this->assertEquals(array('test2', 'test3'), $this->coll->getKeys());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::hasKey
     */
    public function testChecksIfHasKey()
    {
        $this->assertFalse($this->coll->hasKey('test'));
        $this->coll->add('test', 'value');
        $this->assertEquals(true, $this->coll->hasKey('test'));
        $this->coll->add('test2', 'value2');
        $this->assertEquals(true, $this->coll->hasKey('test'));
        $this->assertEquals(true, $this->coll->hasKey('test2'));
        $this->assertFalse($this->coll->hasKey('testing'));
        $this->assertEquals(false, $this->coll->hasKey('AB-C', 'junk'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::hasValue
     */
    public function testChecksIfHasValue()
    {
        $this->assertFalse($this->coll->hasValue('value'));
        $this->coll->add('test', 'value');
        $this->assertEquals('test', $this->coll->hasValue('value'));
        $this->coll->add('test2', 'value2');
        $this->assertEquals('test', $this->coll->hasValue('value'));
        $this->assertEquals('test2', $this->coll->hasValue('value2'));
        $this->assertFalse($this->coll->hasValue('val'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::getAll
     */
    public function testCanGetAllValuesByArray()
    {
        $this->coll->add('foo', 'bar');
        $this->coll->add('tEsT', 'value');
        $this->coll->add('tesTing', 'v2');
        $this->coll->add('key', 'v3');
        $this->assertNull($this->coll->get('test'));
        $this->assertEquals(array(
            'foo'     => 'bar',
            'tEsT'    => 'value',
            'tesTing' => 'v2'
        ), $this->coll->getAll(array(
            'foo', 'tesTing', 'tEsT'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::count
     */
    public function testImplementsCount()
    {
        $data = new Collection();
        $this->assertEquals(0, $data->count());
        $data->add('key', 'value');
        $this->assertEquals(1, count($data));
        $data->add('key', 'value2');
        $this->assertEquals(1, count($data));
        $data->add('key_2', 'value3');
        $this->assertEquals(2, count($data));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::merge
     */
    public function testAddParamsByMerging()
    {
        $params = array(
            'test' => 'value1',
            'test2' => 'value2',
            'test3' => array('value3', 'value4')
        );

        // Add some parameters
        $this->coll->merge($params);

        // Add more parameters by merging them in
        $this->coll->merge(array(
            'test' => 'another',
            'different_key' => 'new value'
        ));

        $this->assertEquals(array(
            'test' => array('value1', 'another'),
            'test2' => 'value2',
            'test3' => array('value3', 'value4'),
            'different_key' => 'new value'
        ), $this->coll->getAll());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::filter
     */
    public function testAllowsFunctionalFilter()
    {
        $this->coll->merge(array(
            'fruit' => 'apple',
            'number' => 'ten',
            'prepositions' => array('about', 'above', 'across', 'after'),
            'same_number' => 'ten'
        ));

        $filtered = $this->coll->filter(function($key, $value) {
            return $value == 'ten';
        });

        $this->assertNotEquals($filtered, $this->coll);

        $this->assertEquals(array(
            'number' => 'ten',
            'same_number' => 'ten'
        ), $filtered->getAll());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::map
     */
    public function testAllowsFunctionalMapping()
    {
        $this->coll->merge(array(
            'number_1' => 1,
            'number_2' => 2,
            'number_3' => 3
        ));

        $mapped = $this->coll->map(function($key, $value) {
            return $value * $value;
        });

        $this->assertNotEquals($mapped, $this->coll);

        $this->assertEquals(array(
            'number_1' => 1,
            'number_2' => 4,
            'number_3' => 9
        ), $mapped->getAll());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::offsetGet
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::offsetSet
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::offsetUnset
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::offsetExists
     */
    public function testImplementsArrayAccess()
    {
        $this->coll->merge(array(
            'k1' => 'v1',
            'k2' => 'v2'
        ));

        $this->assertTrue($this->coll->offsetExists('k1'));
        $this->assertFalse($this->coll->offsetExists('Krull'));

        $this->coll->offsetSet('k3', 'v3');
        $this->assertEquals('v3', $this->coll->offsetGet('k3'));
        $this->assertEquals('v3', $this->coll->get('k3'));

        $this->coll->offsetUnset('k1');
        $this->assertFalse($this->coll->offsetExists('k1'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::filter
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::map
     */
    public function testUsesStaticWhenCreatingNew()
    {
        $qs = new QueryString(array(
            'a' => 'b',
            'c' => 'd'
        ));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\QueryString', $qs->map(function($a, $b) {}));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Common\\Collection', $qs->map(function($a, $b) {}, array(), false));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\QueryString', $qs->filter(function($a, $b) {}));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Common\\Collection', $qs->filter(function($a, $b) {}, false));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::replace
     */
    public function testCanReplaceAllData()
    {
        $this->assertSame($this->coll, $this->coll->replace(array(
            'a' => '123'
        )));

        $this->assertEquals(array(
            'a' => '123'
        ), $this->coll->getAll());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::getPregMatchValue
     */
    public function testReturnsValuesForPregMatch()
    {
        $c = new Collection(array('foo' => 'bar'));
        $this->assertEquals('bar', $c->getPregMatchValue(array(1 => 'foo')));
    }

    public function dataProvider()
    {
        return array(
            array('this_is_a_test', '{ a }_is_a_{ b }', array(
                'a' => 'this',
                'b' => 'test'
            )),
            array('this_is_a_test', '{abc}_is_a_{ 0 }', array(
                'abc' => 'this',
                0 => 'test'
            )),
            array('this_is_a_test', '{ abc }_is_{ not_found }a_{ 0 }', array(
                'abc' => 'this',
                0 => 'test'
            )),
            array('this_is_a_test', 'this_is_a_test', array(
                'abc' => 'this'
            )),
            array('_is_a_', '{ abc }_is_{ not_found }a_{ 0 }', array()),
            array('_is_a_', '{abc}_is_{not_found}a_{0}', array()),
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::inject
     * @dataProvider dataProvider
     */
    public function testInjectsConfigData($output, $input, $config)
    {
        $collection = new Collection($config);
        $this->assertEquals($output, $collection->inject($input));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::keySearch
     */
    public function testCanSearchByKey()
    {
        $collection = new Collection(array(
            'foo' => 'bar',
            'BaZ' => 'pho'
        ));

        $this->assertEquals('foo', $collection->keySearch('FOO'));
        $this->assertEquals('BaZ', $collection->keySearch('baz'));
        $this->assertEquals(false, $collection->keySearch('Bar'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection::fromConfig
     */
    public function testPreparesFromConfig()
    {
        $c = Collection::fromConfig(array(
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
            $c = Collection::fromConfig(null, null, array('a'));
            $this->fail('Exception not throw when missing config');
        } catch (InvalidArgumentException $e) {
        }
    }
}
