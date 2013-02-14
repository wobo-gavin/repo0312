<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryAggregator\PhpAggregator as Ag;

class PhpAggregatorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testEncodes()
    {
        $query = new QueryString();
        $query->useUrlEncoding(false);
        $a = new Ag();
        $key = 't';
        $value = array(
            'v1' => 'a',
            'v2' => 'b',
            'v3' => array(
                'v4' => 'c',
                'v5' => 'd',
            )
        );
        $result = $a->aggregate($key, $value, $query);
        $this->assertEquals(array(
            't[v1]' => 'a',
            't[v2]' => 'b',
            't[v3][v4]' => 'c',
            't[v3][v5]' => 'd',
        ), $result);
    }
}
