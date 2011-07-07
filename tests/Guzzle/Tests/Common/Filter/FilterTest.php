<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Filter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\AbstractFilter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilterCommand;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class FilterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\AbstractFilter::__construct
     */
    public function testConstructorNoParams()
    {
        $filter = new MockFilter();
        $this->assertEquals(array(), $filter->getAll());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\FilterInterface
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\AbstractFilter::__construct
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\AbstractFilter::init
     */
    public function testConstructorWithParams()
    {
        $filter = new MockFilter(array(
            'test' => 'value'
        ));
        $this->assertEquals(array('test' => 'value'), $filter->getAll());
        $filter = new MockFilter(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection(array(
            'test' => 'value'
        )));
        $this->assertEquals(array('test' => 'value'), $filter->getAll());
    }

    /**
     * @covers  \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\AbstractFilter
     * @covers  \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\AbstractFilter::process
     */
    public function testProcess()
    {
        $filter = new MockFilter();
        $command = new MockFilterCommand();
        $this->assertTrue($filter->process($command));
        $this->assertTrue($filter->called);
        $this->assertEquals('modified', $command->value);
        $filter->set('type_hint', 'Blah');
        $this->assertFalse($filter->process($command));
        $filter->set('type_hint', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockFilterCommand');
        $this->assertTrue($filter->process($command));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\AbstractFilter::process
     */
    public function testCannotProcessInvalidTypeHint()
    {
        $filter = new MockFilter(array(
            'type_hint' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\NullObject'
        ));
        $command = new MockFilterCommand();
        $this->assertFalse($filter->process($command));

        $filter = new MockFilter(array(
            'type_hint' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\NullObject'
        ));
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\NullObject();
        $this->assertTrue($filter->process($command));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\ClosureFilter
     */
    public function testClosureFilter()
    {
        $filter = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\ClosureFilter(function($command) {
            return 'closure';
        });

        $command = new MockFilterCommand();
        $this->assertEquals('closure', $filter->process($command));
    }
}