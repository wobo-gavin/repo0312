<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\JsonBodyVisitor as Visitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiParam;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\JsonBodyVisitor
 */
class JsonBodyVisitorTest extends AbstractVisitorTestCase
{
    public function testVisitsLocation()
    {
        $visitor = new Visitor();
        // Test after when no body query values were found
        $visitor->after($this->command, $this->request);
        $visitor->visit($this->command, $this->request, 'test', '123');
        $visitor->visit($this->command, $this->request, 'test2', 'abc');
        $visitor->after($this->command, $this->request);
        $this->assertEquals('{"test":"123","test2":"abc"}', (string) $this->request->getBody());
    }

    public function testAddsJsonHeader()
    {
        $visitor = new Visitor();
        $visitor->setContentTypeHeader('application/json-foo');
        $visitor->visit($this->command, $this->request, 'test', '123');
        $visitor->after($this->command, $this->request);
        $this->assertEquals('application/json-foo', (string) $this->request->getHeader('Content-Type'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\JsonBodyVisitor
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\AbstractVisitor::resolveRecursively
     */
    public function testRecursivelyBuildsJsonBodies()
    {
        $command = $this->getNestedCommand('json');
        $data = new Collection(array());
        $command->validate($data);
        $visitor = new Visitor();
        $visitor->visit($this->command, $this->request, 'Foo', $data['foo'], $command->getParam('foo'));
        $visitor->after($this->command, $this->request);
        $this->assertEquals('{"Foo":{"test":{"baz":true,"Jenga_Yall!":"HELLO"},"bar":123}}', (string) $this->request->getBody());
    }
}
