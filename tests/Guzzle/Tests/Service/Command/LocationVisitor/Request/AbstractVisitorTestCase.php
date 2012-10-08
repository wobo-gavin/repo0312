<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor\Request;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\SchemaValidator;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\OperationCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient;

abstract class AbstractVisitorTestCase extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $command;
    protected $request;
    protected $param;
    protected $validator;

    public function setUp()
    {
        $this->command = new MockCommand();
        $this->request = new EntityEnclosingRequest('POST', 'http://www.test.com');
        $this->validator = new SchemaValidator();
    }

    protected function getCommand($location)
    {
        $command = new OperationCommand(array(), $this->getNestedCommand($location));
        $command->setClient(new MockClient());

        return $command;
    }

    protected function getNestedCommand($location)
    {
        return new Operation(array(
            'httpMethod' => 'POST',
            'parameters' => array(
                'foo' => new Parameter(array(
                    'type'         => 'object',
                    'location'     => $location,
                    'sentAs'       => 'Foo',
                    'required'     => true,
                    'properties'   => array(
                        'test' => array(
                            'type'      => 'object',
                            'required'  => true,
                            'properties' => array(
                                'baz' => array(
                                    'type'    => 'boolean',
                                    'default' => true
                                ),
                                'jenga' => array(
                                    'type'    => 'string',
                                    'default' => 'hello',
                                    'sentAs'  => 'Jenga_Yall!',
                                    'filters' => array('strtoupper')
                                )
                            )
                        ),
                        'bar' => array('default' => 123)
                    )
                ))
            )
        ));
    }
}
