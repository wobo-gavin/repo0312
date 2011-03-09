<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Filter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\AbstractFilter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilterCommand;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ChainTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var Closure
     */
    private $callbackTrue;

    /**
     * @var Closure
     */
    private $callbackFalse;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->callbackTrue = function($filter, $command) {
            return true;
        };

        $this->callbackFalse = function($filter, $command) {
            return false;
        };

        parent::__construct();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain
     */
    public function testConstructorNoParams()
    {
        $chain = new Chain();
        $this->assertFalse($chain->getBreakOnProcess());
        $this->assertEquals(array(), $chain->getFilters());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::__construct
     */
    public function testConstructorWithParams()
    {
        $mock = new MockFilter();
        $chain = new Chain(array($mock), true);
        $this->assertTrue($chain->getBreakOnProcess());
        $this->assertEquals(array($mock), $chain->getFilters());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::addFilter
     */
    public function testAddFilter()
    {
        $chain = new Chain();
        $mock = new MockFilter();
        $this->assertEquals($chain, $chain->addFilter($mock));
        $this->assertEquals(array($mock), $chain->getFilters());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::setBreakOnProcess
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::getBreakOnProcess
     */
    public function testSetBreakOnProcess()
    {
        $chain = new Chain();
        $this->assertFalse($chain->getBreakOnProcess());
        $this->assertInstanceOf('\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain', $chain->setBreakOnProcess(true));
        $this->assertTrue($chain->getBreakOnProcess());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::getFilters
     */
    public function testGetFilters()
    {
        $chain = new Chain();
        $this->assertEquals(array(), $chain->getFilters());
        unset($chain);
        $mock = new MockFilter();
        $chain = new Chain(array($mock));
        $this->assertEquals(array($mock), $chain->getFilters());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::getFilters
     */
    public function testGetFiltersByName()
    {
        $chain = new Chain();
        $mock = new MockFilter();
        $this->assertEquals(array(), $chain->getFilters('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilter'));
        $chain->addFilter($mock);
        $this->assertEquals(array($mock), $chain->getFilters('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilter'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::hasFilter
     */
    public function testHasFilter()
    {
        $chain = new Chain();
        $mock = new MockFilter();
        $this->assertFalse($chain->hasFilter('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilter'));
        $this->assertFalse($chain->hasFilter($mock));
        $chain->addFilter($mock);
        $this->assertTrue($chain->hasFilter('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilter'));
        $this->assertTrue($chain->hasFilter($mock));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::prependFilter
     */
    public function testPrependFilter()
    {
        $chain = new Chain();
        $mock = new MockFilter();
        $mock2 = new MockFilter();
        $chain->addFilter($mock);
        $chain->prependFilter($mock2);
        $this->assertEquals(array($mock2, $mock), $chain->getFilters());
        $chain->prependFilter($mock);
        $this->assertEquals(array($mock, $mock2, $mock), $chain->getFilters());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::process
     */
    public function testProcess()
    {
        $mock = new MockFilter();
        $mock2 = new MockFilter();
        $chain = new Chain(array($mock, $mock2));
        $testValue = new MockFilterCommand();
        $this->assertFalse($mock->called);
        $chain->process($testValue);
        $this->assertTrue($mock->called);
        $this->assertTrue($mock2->called);
        $this->assertEquals('modified', $testValue->value);

        // Test processing when break on process is true
        $mock->called = false;
        $mock2->called = false;
        $chain->setBreakOnProcess(true);
        $chain->process($testValue);
        $this->assertTrue($mock->called);
        $this->assertFalse($mock2->called);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::removeFilter
     */
    public function testRemoveFilter()
    {
        $chain = new Chain();
        $mock = new MockFilter();
        $mock2 = new MockFilter();
        $this->assertEquals(false, $chain->removeFilter($mock2));
        $this->assertEquals(false, $chain->removeFilter($mock));
        $chain->addFilter($mock);
        $this->assertEquals($mock, $chain->removeFilter($mock));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::removeAllFilters
     */
    public function testRemoveAllFilters()
    {
        $chain = new Chain();
        $mock = new MockFilter();
        $this->assertEquals(array(), $chain->removeAllFilters());
        $chain->addFilter($mock);
        $this->assertEquals(array($mock), $chain->removeAllFilters());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::allTrue
     */
    public function testAllTrue()
    {
        $chainFalse = new Chain(array(
            new MockFilter(array('callback' => $this->callbackTrue)),
            new MockFilter(array('callback' => $this->callbackFalse))
        ));
        $this->assertFalse($chainFalse->allTrue(array()));

        $chainTrue = new Chain(array(
            new MockFilter(array('callback' => $this->callbackTrue))
        ));
        $this->assertTrue($chainTrue->allTrue(array()));
        $chainTrue->addFilter(new MockFilter(array('callback' => $this->callbackTrue)));
        $this->assertTrue($chainTrue->allTrue(array()));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::oneTrue
     */
    public function testOneTrue()
    {
        $chain = new Chain(array(
            new MockFilter(array('callback' => $this->callbackTrue)),
            new MockFilter(array('callback' => $this->callbackFalse))
        ));

        $this->assertTrue($chain->oneTrue(array()));

        $chain = new Chain(array(
            new MockFilter(array('callback' => $this->callbackFalse)),
            new MockFilter(array('callback' => $this->callbackFalse))
        ));

        $this->assertFalse($chain->oneTrue(array()));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain::noneTrue
     */
    public function testNoneTrue()
    {
        $chain = new Chain(array(
            new MockFilter(array('callback' => $this->callbackTrue)),
            new MockFilter(array('callback' => $this->callbackFalse))
        ));

        $this->assertFalse($chain->noneTrue(array()));

        $chain = new Chain(array(
            new MockFilter(array('callback' => $this->callbackFalse)),
            new MockFilter(array('callback' => $this->callbackFalse))
        ));
        
        $this->assertTrue($chain->noneTrue(array()));
    }
}