<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Curl;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlVersion;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlVersion
 */
class CurlVersionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testCachesCurlInfo()
    {
        $info = curl_version();
        $instance = CurlVersion::getInstance();

        // Clear out the info cache
        $refObject = new \ReflectionObject($instance);
        $refProperty = $refObject->getProperty('version');
        $refProperty->setAccessible(true);
        $refProperty->setValue($instance, array());

        $this->assertEquals($info, $instance->getAll());
        $this->assertEquals($info, $instance->getAll());

        $this->assertEquals($info['version'], $instance->get('version'));
        $this->assertFalse($instance->get('foo'));
    }

    public function testIsSingleton()
    {
        $refObject = new \ReflectionClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlVersion');
        $refProperty = $refObject->getProperty('instance');
        $refProperty->setAccessible(true);
        $refProperty->setValue(null, null);

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlVersion', CurlVersion::getInstance());
    }
}
