<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\PostFile;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\PostFileVisitor as Visitor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\PostFileVisitor
 */
class PostFileVisitorTest extends AbstractVisitorTestCase
{
    public function testVisitsLocation()
    {
        $visitor = new Visitor();

        // Test using a path to a file
        $visitor->visit($this->command, $this->request, 'test_3', __FILE__);
        $this->assertInternalType('array', $this->request->getPostFile('test_3'));

        // Test with a PostFile
        $visitor->visit($this->command, $this->request, null, new PostFile('baz', __FILE__));
        $this->assertInternalType('array', $this->request->getPostFile('baz'));
    }
}
