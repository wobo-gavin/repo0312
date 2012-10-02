<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor\Request;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\BodyVisitor as Visitor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\BodyVisitor
 */
class BodyVisitorTest extends AbstractVisitorTestCase
{
    public function testVisitsLocation()
    {
        $visitor = new Visitor();
        $param = $this->getNestedCommand('body')->getParam('foo')->setRename('Foo');
        $visitor->visit($this->command, $this->request, $param, '123');
        $this->assertEquals('123', (string) $this->request->getBody());
        $this->assertNull($this->request->getHeader('Expect'));
    }

    public function testAddsExpectHeaderWhenSetToTrue()
    {
        $visitor = new Visitor();
        $param = $this->getNestedCommand('body')->getParam('foo')->setRename('Foo');
        $param->setData('expect_header', true);
        $visitor->visit($this->command, $this->request, $param, '123');
        $this->assertEquals('123', (string) $this->request->getBody());
    }

    public function testCanDisableExpectHeader()
    {
        $visitor = new Visitor();
        $param = $this->getNestedCommand('body')->getParam('foo')->setRename('Foo');
        $param->setData('expect_header', false);
        $visitor->visit($this->command, $this->request, $param, '123');
        $this->assertNull($this->request->getHeader('Expect'));
    }

    public function testCanSetExpectHeaderBasedOnSize()
    {
        $visitor = new Visitor();
        $param = $this->getNestedCommand('body')->getParam('foo')->setRename('Foo');
        // The body is less than the cutoff
        $param->setData('expect_header', 5);
        $visitor->visit($this->command, $this->request, $param, '123');
        $this->assertNull($this->request->getHeader('Expect'));
        // Now check when the body is greater than the cutoff
        $param->setData('expect_header', 2);
        $visitor->visit($this->command, $this->request, $param, '123');
        $this->assertEquals('100-Continue', (string) $this->request->getHeader('Expect'));
    }
}
