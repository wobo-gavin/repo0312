<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\PostFieldVisitor as Visitor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\PostFieldVisitor
 */
class PostFieldVisitorTest extends AbstractVisitorTestCase
{
    public function testVisitsLocation()
    {
        $visitor = new Visitor();
        $visitor->visit($this->command, $this->request, 'test', '123');
        $this->assertEquals('123', (string) $this->request->getPostField('test'));
    }
}
