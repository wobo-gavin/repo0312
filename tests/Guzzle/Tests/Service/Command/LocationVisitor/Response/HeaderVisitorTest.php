<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor\Response;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\HeaderVisitor as Visitor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\HeaderVisitor
 */
class HeaderVisitorTest extends AbstractResponseVisitorTest
{
    public function testVisitsLocation()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'location' => 'header',
            'name'     => 'ContentType',
            'sentAs'   => 'Content-Type'
        ));
        $visitor->visit($this->command, $this->response, $param, $this->value);
        $this->assertEquals('text/plain', $this->value['ContentType']);
    }

    public function testVisitsLocationWithFilters()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'location' => 'header',
            'name'     => 'Content-Type',
            'filters'  => array('strtoupper')
        ));
        $visitor->visit($this->command, $this->response, $param, $this->value);
        $this->assertEquals('TEXT/PLAIN', $this->value['Content-Type']);
    }

    public function testVisitsMappedPrefixHeaders()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'location'             => 'header',
            'name'                 => 'Metadata',
            'sentAs'               => 'X-Baz-',
            'type'                 => 'object',
            'additionalProperties' => array(
                'type' => 'string'
            )
        ));
        $response = new Response(200, array(
            'X-Baz-Test'     => 'ABC',
            'X-Baz-Bar'      => array('123', '456'),
            'Content-Length' => 3
        ), 'Foo');
        $visitor->visit($this->command, $response, $param, $this->value);
        $this->assertEquals(array(
            'Metadata' => array(
                'Test' => 'ABC',
                'Bar'  => array('123', '456')
            )
        ), $this->value);
    }
}
