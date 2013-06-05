<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\ExceptionCollection;

class ExceptionCollectionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    private function getExceptions()
    {
        return array(
            new \Exception('Test'),
            new \Exception('Testing')
        );
    }

    public function testAggregatesExceptions()
    {
        $e = new ExceptionCollection();
        $exceptions = $this->getExceptions();
        $e->add($exceptions[0]);
        $e->add($exceptions[1]);
        $this->assertEquals("(Exception) Test\n(Exception) Testing", $e->getMessage());
        $this->assertSame($exceptions[0], $e->getFirst());
    }

    public function testCanSetExceptions()
    {
        $ex = new \Exception('foo');
        $e = new ExceptionCollection();
        $e->setExceptions(array($ex));
        $this->assertSame($ex, $e->getFirst());
    }

    public function testActsAsArray()
    {
        $e = new ExceptionCollection();
        $exceptions = $this->getExceptions();
        $e->add($exceptions[0]);
        $e->add($exceptions[1]);
        $this->assertEquals(2, count($e));
        $this->assertEquals($exceptions, $e->getIterator()->getArrayCopy());
    }

    public function testCanAddSelf()
    {
        $e1 = new ExceptionCollection();
        $e1->add(new \Exception("Test"));
        $e2 = new ExceptionCollection('Meta description!');
        $e2->add(new \Exception("Test 2"));
        $e1->add($e2);
        $message = $e1->getMessage();
        $this->assertEquals("(Exception) Test\n"
            . "(/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Common\\Exception\\ExceptionCollection)\n"
            . "    Meta description!\n"
            . "    (Exception) Test 2\n", $message);
    }
}
