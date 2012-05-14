<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\JsonServiceBuilderFactory;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\JsonServiceBuilderFactory
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ArrayServiceBuilderFactory
 */
class JsonServiceBuilderFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testBuildsServiceBuilders()
    {
        $j = new JsonServiceBuilderFactory();
        $file = __DIR__ . '/../../TestData/services/json1.json';

        // Initial build
        $builder = $j->build($file);
        // Build it again, get a similar result using the same JsonLoader
        $this->assertEquals($builder, $j->build($file));

        // Ensure that services were parsed
        $this->assertTrue(isset($builder['mock']));
        $this->assertTrue(isset($builder['abstract']));
        $this->assertTrue(isset($builder['foo']));
        $this->assertFalse(isset($builder['jimmy']));
    }
}
