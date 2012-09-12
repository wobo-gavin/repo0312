<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilderAbstractFactory;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilderAbstractFactory
 */
class ServiceBuilderAbstractFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $jsonFile;

    public function setup()
    {
        $this->jsonFile = __DIR__ . '/../../TestData/services/json1.json';
    }

    public function testFactoryDelegatesToConcreteFactories()
    {
        $factory = new ServiceBuilderAbstractFactory();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder', $factory->build($this->jsonFile));
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceBuilderException
     * @expectedExceptionMessage Must pass the name of a .js or .json file or array
     */
    public function testThrowsExceptionWhenInvalidFileExtensionIsPassed()
    {
        $factory = new ServiceBuilderAbstractFactory();
        $factory->build(__FILE__);
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceBuilderException
     * @expectedExceptionMessage Must pass the name of a .js or .json file or array
     */
    public function testThrowsExceptionWhenUnknownTypeIsPassed()
    {
        $factory = new ServiceBuilderAbstractFactory();
        $factory->build(new \stdClass());
    }

    public function configProvider()
    {
        $foo = array(
            'extends' => 'bar',
            'class'   => 'stdClass',
            'params'  => array('a' => 'test', 'b' => '456')
        );

        return array(
            array(
                // Does not extend the existing `foo` service but overwrites it
                array(
                    'services' => array(
                        'foo' => $foo,
                        'bar' => array('params' => array('baz' => '123'))
                    )
                ),
                array(
                    'services' => array(
                        'foo' => array('class' => 'Baz')
                    )
                ),
                array(
                    'services' => array(
                        'foo' => array('class' => 'Baz'),
                        'bar' => array('params' => array('baz' => '123'))
                    )
                )
            ),
            array(
                // Extends the existing `foo` service
                array(
                    'services' => array(
                        'foo' => $foo,
                        'bar' => array('params' => array('baz' => '123'))
                    )
                ),
                array(
                    'services' => array(
                        'foo' => array(
                            'extends' => 'foo',
                            'params'  => array('b' => '123', 'c' => 'def')
                        )
                    )
                ),
                array(
                    'services' => array(
                        'foo' => array(
                            'extends' => 'bar',
                            'class'   => 'stdClass',
                            'params'  => array('a' => 'test', 'b' => '123', 'c' => 'def')
                        ),
                        'bar' => array('params' => array('baz' => '123'))
                    )
                )
            )
        );
    }

    /**
     * @dataProvider configProvider
     */
    public function testCombinesConfigs($a, $b, $c)
    {
        $this->assertEquals($c, ServiceBuilderAbstractFactory::combineConfigs($a, $b));
    }
}
