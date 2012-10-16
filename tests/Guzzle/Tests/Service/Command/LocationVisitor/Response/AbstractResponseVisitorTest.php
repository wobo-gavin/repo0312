<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor\Response;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

abstract class AbstractResponseVisitorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var MockCommand
     */
    protected $command;

    /**
     * @var array
     */
    protected $value;

    public function setUp()
    {
        $this->value = array();
        $this->command = new MockCommand();
        $this->response = new Response(200, array(
            'X-Foo'          => 'bar',
            'Content-Length' => 3,
            'Content-Type'   => 'text/plain'
        ), 'Foo');
    }
}
