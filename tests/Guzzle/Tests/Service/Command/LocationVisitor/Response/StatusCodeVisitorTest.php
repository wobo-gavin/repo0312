<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor\Response;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\StatusCodeVisitor as Visitor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\StatusCodeVisitor
 */
class StatusCodeVisitorTest extends AbstractResponseVisitorTest
{
    public function testVisitsLocation()
    {
        $visitor = new Visitor();
        $param = new Parameter(array('location' => 'statusCode', 'name' => 'code'));
        $visitor->visit($this->command, $this->response, $param, $this->value);
        $this->assertEquals(200, $this->value['code']);
    }
}
