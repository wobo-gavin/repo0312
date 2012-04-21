<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Cookie;

class CookieTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * Data provider for tests
     *
     * @return array
     */
    public function provider()
    {
        return array(
            array('name=value', array(
                'name' => 'value'
            )),
            array('name=value;name2=value 2', array(
                'name' => 'value',
                'name2' => 'value 2'
            )),
            array('name=value;name2=x=y&a=b', array(
                'name' => 'value',
                'name2' => 'x=y&a=b'
            )),
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Cookie::factory
     * @dataProvider provider
     */
    public function testFactoryBuildsCookiesFromCookieStrings($cookieString, array $data)
    {
        $jar = Cookie::factory($cookieString);
        $this->assertEquals($data, $jar->getAll());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Cookie::__construct
     */
    public function testConstructorSetsDefaults()
    {
        $jar = new Cookie();
        $this->assertEquals(';', $jar->getFieldSeparator());
        $this->assertEquals('=', $jar->getValueSeparator());
        $this->assertEquals(false, $jar->isEncodingFields());
        $this->assertEquals(false, $jar->isEncodingValues());
        $this->assertEquals('', $jar->getPrefix());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString::__toString
     * @dataProvider provider
     */
    public function testConvertsToString($cookieString)
    {
        $cookie = Cookie::factory($cookieString);
        $this->assertEquals($cookieString, (string) $cookie);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Cookie
     */
    public function testAggregatesMultipleCookieValues()
    {
        $cookie = Cookie::factory('name=a;name=b');
        $this->assertEquals(array('a', 'b'), $cookie->get('name'));
        $this->assertEquals('name=a;name=b', (string) $cookie);
    }
}
