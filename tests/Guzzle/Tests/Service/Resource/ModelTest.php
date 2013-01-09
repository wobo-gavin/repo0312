<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Resource;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\Model;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\Model
 */
class ModelTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testOwnsStructure()
    {
        $param = new Parameter(array('type' => 'object'));
        $model = new Model(array('foo' => 'bar'), $param);
        $this->assertSame($param, $model->getStructure());
        $this->assertEquals('bar', $model->get('foo'));
        $this->assertEquals('bar', $model['foo']);
    }

    public function testCanBeUsedWithoutStructure()
    {
        $model = new Model(array(
            'Foo' => 'baz',
            'Bar' => array(
                'Boo' => 'Bam'
            )
        ));
        $transform = function ($key, $value) {
            return ($value && is_array($value)) ? new Collection($value) : $value;
        };
        $model = $model->map($transform);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection', $model->getPath('Bar'));
    }

    public function testAllowsFiltering()
    {
        $model = new Model(array(
            'Foo' => 'baz',
            'Bar' => 'a'
        ));
        $model = $model->filter(function ($i, $v) {
            return $v[0] == 'a';
        });
        $this->assertEquals(array('Bar' => 'a'), $model->toArray());
    }
}
