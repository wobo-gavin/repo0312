<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Description;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ArrayDescriptionBuilder;

class ArrayDescriptionBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ArrayDescriptionBuilder::build
     */
    public function testAllowsDeepNestedInheritence()
    {
        $d = ServiceDescription::factory(array(
            'commands' => array(
                'abstract' => array(
                    'method' => 'GET',
                    'params' => array(
                        'test' => array(
                            'type' => 'string',
                            'required' => true
                        )
                    )
                ),
                'abstract2' => array(
                    'path' => '/test',
                    'extends' => 'abstract'
                ),
                'concrete' => array(
                    'extends' => 'abstract2'
                )
            )
        ));

        $c = $d->getCommand('concrete');
        $this->assertEquals('/test', $c->getUri());
        $this->assertEquals('GET', $c->getMethod());
        $params = $c->getParams();
        $param = $params['test'];
        $this->assertEquals('string', $param->getType());
        $this->assertTrue($param->getRequired());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ArrayDescriptionBuilder::build
     * @expectedException RuntimeException
     */
    public function testThrowsExceptionWhenExtendingMissingCommand()
    {
        ServiceDescription::factory(array(
            'commands' => array(
                'concrete' => array(
                    'extends' => 'missing'
                )
            )
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ArrayDescriptionBuilder::build
     */
    public function testRegistersCustomTypes()
    {
        ServiceDescription::factory(array(
            'types' => array(
                'foo' => array(
                    'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Common\\Validation\\Regex',
                    'pattern' => '/[0-9]+/'
                )
            )
        ));

        $valid = Inspector::getInstance()->validateConstraint('foo', 'abc');
        $this->assertEquals('abc does not match the regular expression', $valid);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ArrayDescriptionBuilder::build
     * @expectedException RuntimeException
     * @expectedExceptionMessage Custom types require a class attribute
     */
    public function testCustomTypesRequireClassAttribute()
    {
        ServiceDescription::factory(array(
            'types' => array(
                'slug' => array()
            )
        ));
    }

}
