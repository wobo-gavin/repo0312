<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\XmlElement;

class XmlElementTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @return array
     */
    public function addElementProvider()
    {
        return array(
            array(new XmlElement('<Test />'), 'Foo', '<?xml version="1.0"?>' . PHP_EOL . '<Test><Foo/></Test>' . PHP_EOL),
            array(new XmlElement('<Test />'), new XmlElement('<Foo />'), '<?xml version="1.0"?>' . PHP_EOL . '<Test><Foo></Foo></Test>' . PHP_EOL),
            array(new XmlElement('<Test />'), new XmlElement('<Foo><Bar>123</Bar></Foo>'), '<?xml version="1.0"?>' . PHP_EOL . '<Test><Foo><Bar>123</Bar></Foo></Test>' . PHP_EOL),
            array(new XmlElement('<Test />'), new XmlElement('<Foo><Bar x="abc">123</Bar></Foo>'), '<?xml version="1.0"?>' . PHP_EOL . '<Test><Foo><Bar x="abc">123</Bar></Foo></Test>' . PHP_EOL),
            array(new XmlElement('<Test />'), new XmlElement('<Foo><Bar x="abc"><Baz y="def">123</Baz></Bar></Foo>'), '<?xml version="1.0"?>' . PHP_EOL . '<Test><Foo><Bar x="abc"><Baz y="def">123</Baz></Bar></Foo></Test>' . PHP_EOL)
        );
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\XmlElement::addChild
     * @dataProvider addElementProvider
     */
    public function testAddsChildElementsUsingStringsOrSimpleXmlElements($orig, $add, $result)
    {
        $orig->addChild($add);
        $this->assertEquals($result, $orig->__toString());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\XmlElement::asFormattedXml
     */
    public function testDisplaysXmlDataAsFormattedXmlString()
    {
        $elem = new XmlElement('<Sample />');
        $expected = '<?xml version="1.0"?>' . PHP_EOL . '<Sample/>' . PHP_EOL;
        $this->assertEquals($expected, $elem->asFormattedXml());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\XmlElement::__toString
     */
    public function testAddsToStringMethodToSimpleXmlElement()
    {
        $elem = new XmlElement('<Sample><Test /></Sample>');
        $expected = '<?xml version="1.0"?>' . PHP_EOL . '<Sample><Test/></Sample>' . PHP_EOL;
        $this->assertEquals($expected, $elem->__toString());
    }
}