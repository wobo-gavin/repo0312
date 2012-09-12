<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiParam;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;

abstract class AbstractVisitorTestCase extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $command;
    protected $request;

    public function setUp()
    {
        $this->command = new MockCommand();
        $this->request = new EntityEnclosingRequest('POST', 'http://www.test.com');
    }

    protected function getNestedCommand($location)
    {
        return new ApiCommand(array(
            'params' => array(
                'foo' => new ApiParam(array(
                    'type'         => 'array',
                    'location'     => $location,
                    'location_key' => 'Foo',
                    'required'     => true,
                    'structure'    => array(
                        'test' => array(
                            'type'      => 'array',
                            'required'  => true,
                            'structure' => array(
                                'baz' => array(
                                    'type'    => 'bool',
                                    'default' => true
                                ),
                                // Add a nested parameter that uses a different location_key than the input key
                                'jenga' => array(
                                    'type'         => 'string',
                                    'default'      => 'hello',
                                    'location_key' => 'Jenga_Yall!',
                                    'filters'      => array('strtoupper')
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
