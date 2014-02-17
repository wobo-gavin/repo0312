<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\QueryAggregator;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\QueryAggregator\DuplicateAggregator;

/**
 * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\QueryAggregator\DuplicateAggregator
 * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\QueryAggregator\AbstractAggregator
 */
class DuplicateAggregatorTest extends \PHPUnit_Framework_TestCase
{
    private $encodeData = [
        't' => [
            'v1' => ['a', '1'],
            'v2' => 'b',
            'v3' => ['v4' => 'c', 'v5' => 'd']
        ]
    ];

    public function testEncodes()
    {
        $agg = new DuplicateAggregator();
        $result = $agg->aggregate($this->encodeData);
        $this->assertEquals(array(
            't[v1]' => ['a', '1'],
            't[v2]' => ['b'],
            't[v3][v4]' => ['c'],
            't[v3][v5]' => ['d'],
        ), $result);
    }

    public function testEncodesNoNumericIndices()
    {
        $agg = new DuplicateAggregator(false);
        $result = $agg->aggregate($this->encodeData);
        $this->assertEquals(array(
            't[v1]' => ['a', '1'],
            't[v2]' => ['b'],
            't[v3][v4]' => ['c'],
            't[v3][v5]' => ['d'],
        ), $result);
    }
}
