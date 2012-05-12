<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;

class /* Replaced /* Replaced /* Replaced Guzzle */ */ */Test extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\/* Replaced /* Replaced /* Replaced Guzzle */ */ */
     */
    public function testGetDefaultUserAgent()
    {
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */::reset();
        $version = curl_version();
        $agent = sprintf('/* Replaced /* Replaced /* Replaced Guzzle */ */ *//%s (PHP=%s; curl=%s; openssl=%s)', /* Replaced /* Replaced /* Replaced Guzzle */ */ */::VERSION, \PHP_VERSION, $version['version'], $version['ssl_version']);
        $this->assertEquals($agent, /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent());
        // Get it from cache this time
        $this->assertEquals($agent, /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\/* Replaced /* Replaced /* Replaced Guzzle */ */ */::getHttpDate
     */
    public function testGetHttpDate()
    {
        $fmt = 'D, d M Y H:i:s \G\M\T';
        $this->assertEquals(gmdate($fmt), /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getHttpDate('now'));
        $this->assertEquals(gmdate($fmt), /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getHttpDate(strtotime('now')));
        $this->assertEquals(gmdate($fmt, strtotime('+1 day')), /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getHttpDate('+1 day'));
    }

    public function dataProvider()
    {
        return array(
            array('this_is_a_test', '{ a }_is_a_{ b }', array(
                'a' => 'this',
                'b' => 'test'
            )),
            array('this_is_a_test', '{abc}_is_a_{ 0 }', array(
                'abc' => 'this',
                0 => 'test'
            )),
            array('this_is_a_test', '{ abc }_is_{ not_found }a_{ 0 }', array(
                'abc' => 'this',
                0 => 'test'
            )),
            array('this_is_a_test', 'this_is_a_test', array(
                'abc' => 'this'
            )),
            array('_is_a_', '{ abc }_is_{ not_found }a_{ 0 }', array()),
            array('_is_a_', '{abc}_is_{not_found}a_{0}', array()),
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\/* Replaced /* Replaced /* Replaced Guzzle */ */ */::inject
     * @dataProvider dataProvider
     */
    public function testInjectsConfigData($output, $input, $config)
    {
        $this->assertEquals($output, /* Replaced /* Replaced /* Replaced Guzzle */ */ */::inject($input, new Collection($config)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\/* Replaced /* Replaced /* Replaced Guzzle */ */ */::getCurlInfo
     */
    public function testCachesCurlInfo()
    {
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */::reset();
        $c = curl_version();
        $info = /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getCurlInfo();
        $this->assertInternalType('array', $info);
        $this->assertEquals(false, /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getCurlInfo('ewfewfewfe'));
        $this->assertEquals($c['version'], /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getCurlInfo('version'));
        $this->assertSame(/* Replaced /* Replaced /* Replaced Guzzle */ */ */::getCurlInfo(), $info);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\/* Replaced /* Replaced /* Replaced Guzzle */ */ */::getCurlInfo
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\/* Replaced /* Replaced /* Replaced Guzzle */ */ */::reset
     */
    public function testDeterminesIfCurlCanFollowLocation()
    {
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */::reset();
        if (!ini_get('open_basedir')) {
            $this->assertTrue(/* Replaced /* Replaced /* Replaced Guzzle */ */ */::getCurlInfo('follow_location'));
        } else {
            $this->assertFalse(/* Replaced /* Replaced /* Replaced Guzzle */ */ */::getCurlInfo('follow_location'));
        }
    }
}
