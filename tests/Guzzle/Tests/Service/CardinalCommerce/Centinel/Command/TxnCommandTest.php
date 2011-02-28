<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\CardinalCommerce\Centinel\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Txn;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class TxnTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Txn
     */
    public function testBuiltFromArrayAndExcludesSpecialValues()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('lookup', array(
            'transaction_type' => 'C',
            'test_timeout' => 'xyz'
        ));

        $request = $command->prepare($/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertTrue($request->getPostFields()->hasKey('cmpi_msg'));
        $message = $request->getPostFields()->get('cmpi_msg');
        
        $xml = new \SimpleXMLElement($message);
        $this->assertEquals('PHPTC', (string)$xml->Source);
        $this->assertEquals('1.7', (string)$xml->SourceVersion);
        $this->assertEquals('15000', (string)$xml->ResolveTimeout);
        $this->assertEquals('15000', (string)$xml->ReceiveTimeout);
        $this->assertEquals('15000', (string)$xml->ConnectTimeout);
        $this->assertEquals('https://centineltest.cardinalcommerce.com/maps/txns.asp', (string)$xml->TransactionUrl);
        $this->assertTrue('' != (string)$xml->MerchantSystemDate);
        $this->assertEquals('cmpi_lookup', (string)$xml->MsgType);
        $this->assertEquals('C', (string)$xml->TransactionType);
        $this->assertEquals('', (string)$xml->headers);
        $this->assertEquals('', (string)$xml->default_timeout);
        $this->assertEquals('', (string)$xml->test_timeout);
        $this->assertEquals('', (string)$xml->timeout);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Txn
     */
    public function testHandlesAndIgnoreSpecialValuesInPayload()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');
        $command = new Txn(array(
            'transaction_type' => 'C',
            'test_timeout' => 'xyz',
            'par_es_payload' => 'test',
            'ip_address' => '192.168.16.121',
            'Item_Name_1' => 'item_1',
            'Item_Name_212' => 'item_number_212'
        ));

        $request = $command->prepare($/* Replaced /* Replaced /* Replaced client */ */ */);
        $message = $request->getPostFields()->get('cmpi_msg');

        $xml = new \SimpleXMLElement($message);
        $this->assertEquals('test', (string)$xml->PAResPayload);
        $this->assertEquals('192.168.16.121', (string)$xml->IPAddress);
        $this->assertEquals('item_1', (string)$xml->Item_Name_1);
        $this->assertEquals('item_number_212', (string)$xml->Item_Name_212);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Txn
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\CentinelErrorResponseException
     */
    public function testThrowsExceptionOnError()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'UnsupportedMethod');
        // $this->enableClientDebug($/* Replaced /* Replaced /* Replaced client */ */ */);
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('txn', array(
            'MsgType' => 'cmpi_lookup',
            'TransactionType' => 'C'
        ));

        $request = $command->prepare($/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('centineltest.cardinalcommerce.com', $request->getHost());
        $this->assertEquals('https', $request->getScheme());
        $this->assertEquals('/maps/txns.asp', $request->getPath());

        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Txn
     */
    public function testGetsXmlResponse()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'LookupResponse');
        // $this->enableClientDebug($/* Replaced /* Replaced /* Replaced client */ */ */);
        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('txn', array(
            'MsgType' => 'cmpi_lookup',
            'TransactionType' => 'C'
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('75f986t76f6', (string)$command->getResult()->TransactionId);
    }

    /**
     * Tests the currency conversion for Cardinal to convert money to pennies
     *
     * @return array
     */
    public function currencyDataProvider()
    {
        return array(
            array(.99, 99),
            array('0.99', 99),
            array('$5,458.21', 545821),
            array('$1', 100),
            array('$1.2', 120),
            array(123.123, 12312),
            array(123.126, 12313),
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Txn::convertCurrency
     * @dataProvider currencyDataProvider
     */
    public function testConvertsCurrency($in, $out)
    {
        $txn = new Txn();
        $this->assertEquals($out, $txn->convertCurrency($in));
    }
}