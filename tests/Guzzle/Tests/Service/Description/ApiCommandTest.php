<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Description;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inspector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiParam;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ValidationException;

/**
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ test type="type:object"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bool_1 default="true" type="boolean"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bool_2 default="false"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ float type="float"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ int type="integer"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ date type="date"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ timestamp type="time"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ string type="string"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ username required="true" filters="strtolower"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ dynamic default="{username}_{ string }_{ does_not_exist }"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ test_function type="string" filters="/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Description\ApiCommandTest::strtoupper"
 */
class ApiCommandTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public static function strtoupper($string)
    {
        return strtoupper($string);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand
     */
    public function testApiCommandIsDataObject()
    {
        $c = new ApiCommand(array(
            'name'    => 'test',
            'doc'     => 'doc',
            'doc_url' => 'http://www.example.com',
            'method'  => 'POST',
            'uri'     => '/api/v1',
            'result_type' => 'array',
            'result_doc'  => 'returns the json_decoded response',
            'deprecated'  => true,
            'params' => array(
                'key' => array(
                    'required'   => 'true',
                    'type'       => 'string',
                    'max_length' => 10
                ),
                'key_2' => array(
                    'required' => 'true',
                    'type'     => 'integer',
                    'default'  => 10
                )
           )
        ));

        $this->assertEquals('test', $c->getName());
        $this->assertEquals('doc', $c->getDoc());
        $this->assertEquals('http://www.example.com', $c->getDocUrl());
        $this->assertEquals('POST', $c->getMethod());
        $this->assertEquals('/api/v1', $c->getUri());
        $this->assertEquals('array', $c->getResultType());
        $this->assertEquals('returns the json_decoded response', $c->getResultDoc());
        $this->assertTrue($c->isDeprecated());
        $this->assertEquals('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\DynamicCommand', $c->getConcreteClass());
        $this->assertEquals(array(
            'key' => new ApiParam(array(
                'name' => 'key',
                'required' => 'true',
                'type' => 'string',
                'max_length' => 10
            )),
            'key_2' => new ApiParam(array(
                'name' => 'key_2',
                'required' => 'true',
                'type' => 'integer',
                'default' => 10
            ))
        ), $c->getParams());

        $this->assertEquals(new ApiParam(array(
            'name' => 'key_2',
            'required' => 'true',
            'type' => 'integer',
            'default' => 10
        )), $c->getParam('key_2'));

        $this->assertNull($c->getParam('afefwef'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::__construct
     */
    public function testAllowsConcreteCommands()
    {
        $c = new ApiCommand(array(
            'name' => 'test',
            'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\ClosureCommand',
            'params' => array(
                'p' => new ApiParam(array(
                    'name' => 'foo'
                ))
            )
        ));
        $this->assertEquals('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\ClosureCommand', $c->getConcreteClass());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::toArray
     */
    public function testConvertsToArray()
    {
        $data = array(
            'name'      => 'test',
            'class'     => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\ClosureCommand',
            'doc'       => 'test',
            'doc_url'   => 'http://www.example.com',
            'method'    => 'PUT',
            'uri'       => '/',
            'params'    => array(
                'p' => array(
                    'name' => 'foo'
                )
            ),
            'result_type' => null,
            'result_doc'  => null,
            'deprecated'  => false
        );
        $c = new ApiCommand($data);
        $toArray = $c->toArray();
        $this->assertArrayHasKey('params', $toArray);
        $this->assertInternalType('array', $toArray['params']);

        // Normalize the array
        unset($data['params']);
        unset($toArray['params']);
        $this->assertEquals($data, $toArray);
    }

    /**
     * Clear the class cache of the ApiCommand static factory method
     */
    protected function clearCommandCache()
    {
        $refObject = new \ReflectionClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand');
        $refProperty = $refObject->getProperty('apiCommandCache');
        $refProperty->setAccessible(true);
        $refProperty->setValue(null, array());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::fromCommand
     */
    public function testDoesNotErrorWhenNoAnnotationsArePresent()
    {
        $this->clearCommandCache();

        $command = ApiCommand::fromCommand('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\Sub\\Sub');
        $this->assertEquals(array(), $command->getParams());

        // Ensure that the cache returns the same value
        $this->assertSame($command, ApiCommand::fromCommand('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\Sub\\Sub'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::fromCommand
     */
    public function testBuildsApiParamFromClassDocBlock()
    {
        $command = ApiCommand::fromCommand('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\MockCommand');
        $this->assertEquals(3, count($command->getParams()));

        $this->assertTrue($command->getParam('test')->getRequired());
        $this->assertEquals('123', $command->getParam('test')->getDefault());
        $this->assertEquals('Test argument', $command->getParam('test')->getDoc());

        $this->assertEquals('abc', $command->getParam('_internal')->getDefault());
    }

    protected function getApiCommand()
    {
        return ApiCommand::fromCommand(get_class($this));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::validate
     */
    public function testAddsDefaultAndInjectsConfigs()
    {
        $col = new Collection(array(
            'username' => 'user',
            'string'   => 'test',
            'float'    => 1.23
        ));

        $this->getApiCommand()->validate($col);
        $this->assertEquals(false, $col->get('bool_2'));
        $this->assertEquals('user_test_', $col->get('dynamic'));
        $this->assertEquals(1.23, $col->get('float'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::validate
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ValidationException
     */
    public function testValidatesTypeHints()
    {
        $this->getApiCommand()->validate(new Collection(array(
            'test' => 'uh oh',
            'username' => 'test'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::validate
     */
    public function testConvertsBooleanDefaults()
    {
        $c = new Collection(array(
            'test' => $this,
            'username' => 'test'
        ));

        $this->getApiCommand()->validate($c);
        $this->assertTrue($c->get('bool_1'));
        $this->assertFalse($c->get('bool_2'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::validate
     */
    public function testValidatesArgs()
    {
        $config = new Collection(array(
            'data' => 123,
            'min'  => 'a',
            'max'  => 'aaa'
        ));

        $command = new ApiCommand(array(
            'params' => array(
                'data' => new ApiParam(array(
                    'type' => 'string'
                )),
                'min' => new ApiParam(array(
                    'type' => 'string',
                    'min_length' => 2
                )),
                'max' => new ApiParam(array(
                    'type' => 'string',
                    'max_length' => 2
                ))
            )
        ));

        try {
            $command->validate($config);
            $this->fail('Did not throw expected exception');
        } catch (ValidationException $e) {
            $concat = implode("\n", $e->getErrors());
            $this->assertContains("Value must be of type string", $concat);
            $this->assertContains("Requires that the min argument be >= 2 characters", $concat);
            $this->assertContains("Requires that the max argument be <= 2 characters", $concat);
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::validate
     */
    public function testRunsValuesThroughFilters()
    {
        $data = new Collection(array(
            'username'      => 'TEST',
            'test_function' => 'foo'
        ));

        $this->getApiCommand()->validate($data);
        $this->assertEquals('test', $data->get('username'));
        $this->assertEquals('FOO', $data->get('test_function'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::validate
     */
    public function testTypeValidationCanBeDisabled()
    {
        $i = Inspector::getInstance();
        $i->setTypeValidation(false);

        $command = $this->getTestCommand();
        $command->validate(new Collection(array(
            'data' => new \stdClass()
        )), $i);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::validate
     */
    public function testSkipsFurtherValidationIfNotSet()
    {
        $command = $this->getTestCommand();
        $command->validate(new Collection());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::validate
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ValidationException
     * @expectedExceptionMessage Validation errors: Requires that the data argument be supplied.
     */
    public function testValidatesRequiredFieldsAreSet()
    {
        $command = new ApiCommand(array(
            'params' => array(
                'data' => new ApiParam(array(
                    'required' => true
                ))
            )
        ));

        $command->validate(new Collection());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::hasParam
     */
    public function testDeterminesIfHasParam()
    {
        $command = $this->getTestCommand();
        $this->assertTrue($command->hasParam('data'));
        $this->assertFalse($command->hasParam('baz'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::getParamNames
     */
    public function testReturnsParamNames()
    {
        $command = $this->getTestCommand();
        $this->assertEquals(array('data'), $command->getParamNames());
    }

    /**
     * @return ApiCommand
     */
    protected function getTestCommand()
    {
        return new ApiCommand(array(
            'params' => array(
                'data' => new ApiParam(array(
                    'type' => 'string'
                ))
            )
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand
     */
    public function testCanBuildUpCommands()
    {
        $c = new ApiCommand(array());
        $c->setName('foo')
            ->setConcreteClass('Baz')
            ->setDeprecated(false)
            ->setDoc('doc')
            ->setDocUrl('http://www.foo.com')
            ->setMethod('PUT')
            ->setResultDoc('oh')
            ->setResultType('string')
            ->setUri('/foo/bar')
            ->addParam(new ApiParam(array(
                'name' => 'test'
            )));

        $this->assertEquals('foo', $c->getName());
        $this->assertEquals('Baz', $c->getConcreteClass());
        $this->assertEquals(false, $c->isDeprecated());
        $this->assertEquals('doc', $c->getDoc());
        $this->assertEquals('http://www.foo.com', $c->getDocUrl());
        $this->assertEquals('PUT', $c->getMethod());
        $this->assertEquals('oh', $c->getResultDoc());
        $this->assertEquals('string', $c->getResultType());
        $this->assertEquals('/foo/bar', $c->getUri());
        $this->assertEquals(array('test'), $c->getParamNames());
    }
}
