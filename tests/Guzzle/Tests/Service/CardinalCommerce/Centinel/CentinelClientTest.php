<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\CardinalCommerce\Centinel;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class CentinelClientTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
     /**
      * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\CentinelClient
     */
    public function testConstructor()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');
        $this->assertEquals('https://centineltest.cardinalcommerce.com/maps/txns.asp', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());

        $this->assertEquals('test', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('password'));
        $this->assertEquals('123', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('processor_id'));
        $this->assertEquals('456', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('merchant_id'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\CentinelClient
     */
    public function testHandlesPayloads()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');

        // Make sure empty values just return an empty array
        $this->assertEquals(array(), $/* Replaced /* Replaced /* Replaced client */ */ */->unpackPayload(''));
        
        $payload = $/* Replaced /* Replaced /* Replaced client */ */ */->generatePayload(array(
            'test' => 'data',
            'abc' => '123',
            'TransactionPwd' => 'abc'
        ));

        $unpacked = $/* Replaced /* Replaced /* Replaced client */ */ */->unpackPayload($payload);
        $this->assertArrayHasKey('Hash', $unpacked);
        $this->assertArrayNotHasKey('TransactionPwd', $unpacked);
        unset($unpacked['Hash']);
        $this->assertEquals('data', $unpacked['test']);
        $this->assertEquals('123', $unpacked['abc']);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\CentinelClient
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\InvalidPayloadException
     */
    public function testHandlesPayloadValidation()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');

        $payload = $/* Replaced /* Replaced /* Replaced client */ */ */->generatePayload(array(
            'test' => 'data',
            'abc' => '123'
        ));
        parse_str($payload, $data);
        $data['Hash'] = 'invalid';
        $data = new QueryString($data);
        $data->setPrefix('');
        $payload = (string)$data;

        $unpacked = $/* Replaced /* Replaced /* Replaced client */ */ */->unpackPayload($payload);
    }
}