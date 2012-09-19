<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor\Request;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\PostFieldVisitor as Visitor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\PostFieldVisitor
 */
class PostFieldVisitorTest extends AbstractVisitorTestCase
{
    public function testVisitsLocation()
    {
        $visitor = new Visitor();
        $param = $this->getNestedCommand('postField')->getParam('foo');
        $visitor->visit($this->command, $this->request, $param->setRename('test'), '123');
        $this->assertEquals('123', (string) $this->request->getPostField('test'));
    }

    public function testRecursivelyBuildsPostFields()
    {
        $command = $this->getCommand('postField');
        $command->getOperation()->getParam('foo')->setRename('Foo');
        $request = $command->prepare();
        $visitor = new Visitor();
        $param = $command->getOperation()->getParam('foo');
        $visitor->visit($command, $request, $param, $command['foo']);
        $visitor->after($command, $request);
        $this->assertEquals(
            'Foo[test][baz]=1&Foo[test][Jenga_Yall!]=HELLO&Foo[bar]=123',
            rawurldecode((string) $request->getPostFields())
        );
    }
}
