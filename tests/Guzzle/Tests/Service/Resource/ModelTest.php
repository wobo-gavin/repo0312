<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Resource;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Resource\Model;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;

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

    public function testRetrievesNestedKeysUsingPath()
    {
        $data = array(
            'foo' => 'bar',
            'baz' => array(
                'mesa' => array(
                    'jar' => 'jar'
                )
            )
        );
        $param = new Parameter(array('type' => 'object'));
        $model = new Model($data, $param);
        $this->assertSame($param, $model->getStructure());
        $this->assertEquals('bar', $model->getPath('foo'));
        $this->assertEquals('jar', $model->getPath('baz/mesa/jar'));
        $this->assertNull($model->getPath('wewewf'));
        $this->assertNull($model->getPath('baz/mesa/jar/jar'));
        $this->assertSame($data, $model->toArray());
    }
}
