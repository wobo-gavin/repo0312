<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ArrayServiceBuilderFactory;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ArrayServiceBuilderFactory
 */
class ArrayServiceBuilderFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testBuildsServiceBuilders()
    {
        $arrayFactory = new ArrayServiceBuilderFactory();

        $data = array(
            'services' => array(
                'abstract' => array(
                    'params' => array(
                        'access_key' => 'xyz',
                        'secret' => 'abc',
                    ),
                ),
                'foo' => array(
                    'extends' => 'abstract',
                    'params' => array(
                        'baz' => 'bar',
                    ),
                ),
                'mock' => array(
                    'extends' => 'abstract',
                    'params' => array(
                        'username' => 'foo',
                        'password' => 'baz',
                        'subdomain' => 'bar',
                    )
                )
            )
        );

        $builder = $arrayFactory->build($data);

        // Ensure that services were parsed
        $this->assertTrue(isset($builder['mock']));
        $this->assertTrue(isset($builder['abstract']));
        $this->assertTrue(isset($builder['foo']));
        $this->assertFalse(isset($builder['jimmy']));
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceNotFoundException
     * @expectedExceptionMessage foo is trying to extend a non-existent service: abstract
     */
    public function testThrowsExceptionWhenExtendingNonExistentService()
    {
        $arrayFactory = new ArrayServiceBuilderFactory();

        $data = array(
            'services' => array(
                'foo' => array(
                    'extends' => 'abstract'
                )
            )
        );

        $builder = $arrayFactory->build($data);
    }

    public function testAllowsGlobalParameterOverrides()
    {
        $arrayFactory = new ArrayServiceBuilderFactory();

        $data = array(
            'services' => array(
                'foo' => array(
                    'params' => array(
                        'foo' => 'baz',
                        'bar' => 'boo'
                    )
                )
            )
        );

        $builder = $arrayFactory->build($data, array(
            'bar' => 'jar',
            'far' => 'car'
        ));

        $compiled = json_decode($builder->serialize(), true);
        $this->assertEquals(array(
            'foo' => 'baz',
            'bar' => 'jar',
            'far' => 'car'
        ), $compiled['foo']['params']);
    }

    public function tstDoesNotErrorOnCircularReferences()
    {
        $arrayFactory = new ArrayServiceBuilderFactory();
        $arrayFactory->build(array(
            'services' => array(
                'too' => array('extends' => 'ball'),
                'ball' => array('extends' => 'too'),
            )
        ));
    }
}
