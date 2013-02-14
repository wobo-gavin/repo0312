<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryAggregator\DuplicateAggregator as Ag;

class DuplicateAggregatorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testAggregates()
    {
        $query = new QueryString();
        $a = new Ag();
        $key = 'facet 1';
        $value = array('size a', 'width b');
        $result = $a->aggregate($key, $value, $query);
        $this->assertEquals(array('facet%201' => array('size%20a', 'width%20b')), $result);
    }

    public function testEncodes()
    {
        $query = new QueryString();
        $query->useUrlEncoding(false);
        $a = new Ag();
        $key = 'facet 1';
        $value = array('size a', 'width b');
        $result = $a->aggregate($key, $value, $query);
        $this->assertEquals(array('facet 1' => array('size a', 'width b')), $result);
    }
}
