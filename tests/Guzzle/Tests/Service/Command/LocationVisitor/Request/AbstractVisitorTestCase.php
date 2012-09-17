<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor\Request;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;

abstract class AbstractVisitorTestCase extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $command;
    protected $request;
    protected $param;

    public function setUp()
    {
        $this->command = new MockCommand();
        $this->request = new EntityEnclosingRequest('POST', 'http://www.test.com');
    }

    protected function getNestedCommand($location)
    {
        return new Operation(array(
            'parameters' => array(
                'foo' => new Parameter(array(
                    'type'         => 'object',
                    'location'     => $location,
                    'location_key' => 'Foo',
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
                                // Add a nested parameter that uses a different location_key than the input key
                                'jenga' => array(
                                    'type'    => 'string',
                                    'default' => 'hello',
                                    'rename'  => 'Jenga_Yall!',
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
