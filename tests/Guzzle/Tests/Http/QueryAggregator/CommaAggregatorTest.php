<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryAggregator\CommaAggregator as Ag;

class CommaAggregatorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testAggregates()
    {
        $query = new QueryString();
        $a = new Ag();
        $key = 'test 123';
        $value = array('foo 123', 'baz', 'bar');
        $result = $a->aggregate($key, $value, $query);
        $this->assertEquals(array('test%20123' => 'foo%20123,baz,bar'), $result);
    }

    public function testEncodes()
    {
        $query = new QueryString();
        $query->useUrlEncoding(false);
        $a = new Ag();
        $key = 'test 123';
        $value = array('foo 123', 'baz', 'bar');
        $result = $a->aggregate($key, $value, $query);
        $this->assertEquals(array('test 123' => 'foo 123,baz,bar'), $result);
    }
}
