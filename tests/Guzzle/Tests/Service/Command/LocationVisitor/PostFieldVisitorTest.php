<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
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

    public function testRecursivelyBuildsPostFields()
    {
        $command = $this->getNestedCommand('post_field');
        $data = new Collection();
        $command->validate($data);
        $visitor = new Visitor();
        $visitor->visit($this->command, $this->request, 'Foo', $data['foo'], $command->getParam('foo'));
        $visitor->after($this->command, $this->request);
        $this->assertEquals(
            'Foo[test][baz]=1&Foo[test][Jenga_Yall!]=HELLO&Foo[bar]=123',
            rawurldecode((string) $this->request->getPostFields())
        );
    }
}
