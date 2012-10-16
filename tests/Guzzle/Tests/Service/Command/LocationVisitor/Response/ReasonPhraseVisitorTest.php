<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor\Response;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\ReasonPhraseVisitor as Visitor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\ReasonPhraseVisitor
 */
class ReasonPhraseVisitorTest extends AbstractResponseVisitorTest
{
    public function testVisitsLocation()
    {
        $visitor = new Visitor();
        $param = new Parameter(array('location' => 'reasonPhrase', 'name' => 'phrase'));
        $visitor->visit($this->command, $this->response, $param, $this->value);
        $this->assertEquals('OK', $this->value['phrase']);
    }
}
