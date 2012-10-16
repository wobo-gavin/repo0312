<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command\LocationVisitor\Response;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\XmlVisitor as Visitor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response\XmlVisitor
 */
class XmlVisitorTest extends AbstractResponseVisitorTest
{
    public function testCanExtractAndRenameTopLevelXmlValues()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'location' => 'xml',
            'name'     => 'foo',
            'sentAs'   => 'Bar'
        ));
        $value = array('Bar' => 'test');
        $visitor->visit($this->command, $this->response, $param, $value);
        $this->assertArrayHasKey('foo', $value);
        $this->assertEquals('test', $value['foo']);
    }

    public function testEnsuresRepeatedArraysAreInCorrectLocations()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'location' => 'xml',
            'name'     => 'foo',
            'sentAs'   => 'Foo',
            'type'     => 'array',
            'items'    => array(
                'type' => 'object',
                'properties' => array(
                    'Bar' => array('type' => 'string'),
                    'Baz' => array('type' => 'string'),
                    'Bam' => array('type' => 'string')
                )
            )
        ));

        $xml = new \SimpleXMLElement('<Test><Foo><Bar>1</Bar><Baz>2</Baz></Foo></Test>');
        $value = json_decode(json_encode($xml), true);
        $visitor->visit($this->command, $this->response, $param, $value);
        $this->assertEquals(array(
            'foo' => array(
                array (
                    'Bar' => '1',
                    'Baz' => '2'
                )
            )
        ), $value);
    }

    public function testEnsuresFlatArraysAreFlat()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'location' => 'xml',
            'name'     => 'foo',
            'type'     => 'array',
            'items'    => array('type' => 'string')
        ));

        $value = array('foo' => array('bar', 'baz'));
        $visitor->visit($this->command, $this->response, $param, $value);
        $this->assertEquals(array('foo' => array('bar', 'baz')), $value);

        $value = array('foo' => 'bar');
        $visitor->visit($this->command, $this->response, $param, $value);
        $this->assertEquals(array('foo' => array('bar')), $value);
    }

    public function xmlDataProvider()
    {
        $param = new Parameter(array(
            'location' => 'xml',
            'name'     => 'Items',
            'type'     => 'array',
            'items'    => array(
                'type' => 'object',
                'name' => 'Item',
                'properties' => array(
                    'Bar' => array('type' => 'string'),
                    'Baz' => array('type' => 'string')
                )
            )
        ));

        return array(
            array($param, '<Test><Items><Item><Bar>1</Bar></Item><Item><Bar>2</Bar></Item></Items></Test>', array(
                'Items' => array(
                    array('Bar' => 1),
                    array('Bar' => 2)
                )
            )),
            array($param, '<Test><Items><Item><Bar>1</Bar></Item></Items></Test>', array(
                'Items' => array(
                    array('Bar' => 1)
                )
            )),
            array($param, '<Test><Items /></Test>', array(
                'Items' => array()
            ))
        );
    }

    /**
     * @dataProvider xmlDataProvider
     */
    public function testEnsuresWrappedArraysAreInCorrectLocations($param, $xml, $result)
    {
        $visitor = new Visitor();
        $xml = new \SimpleXMLElement($xml);
        $value = json_decode(json_encode($xml), true);
        $visitor->visit($this->command, $this->response, $param, $value);
        $this->assertEquals($result, $value);
    }

    public function testCanRenameValues()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'name'     => 'TerminatingInstances',
            'type'     => 'array',
            'location' => 'xml',
            'sentAs'   => 'instancesSet',
            'items'    => array(
                'name' => 'item',
                'type' => 'object',
                'sentAs' => 'item',
                'properties' => array(
                    'InstanceId' => array(
                        'type'   => 'string',
                        'sentAs' => 'instanceId',
                    ),
                    'CurrentState' => array(
                        'type'   => 'object',
                        'sentAs' => 'currentState',
                        'properties' => array(
                            'Code' => array(
                                'type' => 'numeric',
                                'sentAs' => 'code',
                            ),
                            'Name' => array(
                                'type' => 'string',
                                'sentAs' => 'name',
                            ),
                        ),
                    ),
                    'PreviousState' => array(
                        'type'   => 'object',
                        'sentAs' => 'previousState',
                        'properties' => array(
                            'Code' => array(
                                'type' => 'numeric',
                                'sentAs' => 'code',
                            ),
                            'Name' => array(
                                'type' => 'string',
                                'sentAs' => 'name',
                            ),
                        ),
                    ),
                ),
            )
        ));

        $value = array(
            'instancesSet' => array (
                'item' => array (
                    'instanceId' => 'i-3ea74257',
                    'currentState' => array(
                        'code' => '32',
                        'name' => 'shutting-down',
                    ),
                    'previousState' => array(
                        'code' => '16',
                        'name' => 'running',
                    ),
                ),
            )
        );

        $visitor->visit($this->command, $this->response, $param, $value);

        $this->assertEquals(array(
            'TerminatingInstances' => array(
                array(
                    'InstanceId' => 'i-3ea74257',
                    'CurrentState' => array(
                        'Code' => '32',
                        'Name' => 'shutting-down',
                    ),
                    'PreviousState' => array(
                        'Code' => '16',
                        'Name' => 'running',
                    )
                )
            )
        ), $value);
    }

    public function testAddsEmptyArraysWhenValueIsMissing()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'name'     => 'Foo',
            'type'     => 'array',
            'location' => 'xml',
            'items' => array(
                'type' => 'object',
                'properties' => array(
                    'Baz' => array('type' => 'array'),
                    'Bar' => array(
                        'type'   => 'object',
                        'properties' => array(
                            'Baz' => array('type' => 'array'),
                         )
                    )
                )
            )
        ));

        $value = array();
        $visitor->visit($this->command, $this->response, $param, $value);
        $this->assertEquals(array(
            'Foo' => array()
        ), $value);

        $value = array(
            'Foo' => array(
                'Bar' => array()
            )
        );
        $visitor->visit($this->command, $this->response, $param, $value);
        $this->assertEquals(array(
            'Foo' => array(
                array(
                    'Baz' => array(),
                    'Bar' => array(
                        'Baz' => array()
                    )
                )
            )
        ), $value);
    }

    public function testAddsBooleanWhenValueIsMissing()
    {
        $visitor = new Visitor();
        $param = new Parameter(array(
            'name'     => 'Foo',
            'type'     => 'boolean',
            'location' => 'xml'
        ));

        $value = null;
        $visitor->visit($this->command, $this->response, $param, $value);
        $this->assertSame(array('Foo' => false), $value);
    }
}
