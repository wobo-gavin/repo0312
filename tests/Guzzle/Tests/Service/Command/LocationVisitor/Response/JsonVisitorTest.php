<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor\Response;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\JsonVisitor as Visitor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\JsonVisitor
 */
class JsonVisitorTest extends AbstractResponseVisitorTest
{
    public function testVisitsLocation()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'name' => 'foo',
            'type' => 'array',
            'items' => array(
                'filters' => 'strtoupper',
                'type'    => 'string'
            )
        ));
        $this->value = array('foo' => array('a', 'b', 'c'));
        $visitor->visit($this->command, $this->response, $param, $this->value);
        $this->assertEquals(array('A', 'B', 'C'), $this->value['foo']);
    }

    public function testRenamesTopLevelValues()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'name'   => 'foo',
            'sentAs' => 'Baz',
            'type'   => 'string',
        ));
        $this->value = array('Baz' => 'test');
        $visitor->visit($this->command, $this->response, $param, $this->value);
        $this->assertEquals(array('foo' => 'test'), $this->value);
    }

    public function testTraversesObjectsAndAppliesFilters()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'name' => 'foo',
            'type' => 'object',
            'properties' => array(
                'foo' => array('filters' => 'strtoupper'),
                'bar' => array('filters' => 'strtolower')
            )
        ));
        $this->value = array('foo' => array('foo' => 'hello', 'bar' => 'THERE'));
        $visitor->visit($this->command, $this->response, $param, $this->value);
        $this->assertEquals(array('foo' => 'HELLO', 'bar' => 'there'), $this->value['foo']);
    }
}