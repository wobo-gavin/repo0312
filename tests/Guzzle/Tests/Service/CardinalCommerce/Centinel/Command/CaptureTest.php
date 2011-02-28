<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\CardinalCommerce\Centinel\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Capture;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\CentinelClient;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class CaptureTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Capture
     */
    public function testCapture()
    {
        $c = new Capture();
        $this->assertSame($c, $c->setAmount(12.99));
        $this->assertSame($c, $c->setCarrier('UPS'));
        $this->assertSame($c, $c->setCurrencyCode(CentinelClient::CURRENCY_US));
        $this->assertSame($c, $c->setOrderDescription('test'));
        $this->assertSame($c, $c->setOrderId('123'));
        $this->assertSame($c, $c->setShipMethodName('Ground')); // not sure about what goes here
        $this->assertSame($c, $c->setTrackingNumber('xyz'));
        $this->assertSame($c, $c->setTransactionType(CentinelClient::TYPE_AMAZON));
        $this->assertSame($c, $c->addProduct('ABC', 2));
        $this->assertSame($c, $c->addProduct('DEF', 1));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'CaptureResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($c);

        // Check the outbound cmpi_capture message
        $xml = new \SimpleXMLElement(trim($c->getRequest()->getPostFields()->get('cmpi_msg')));
        $this->assertEquals('cmpi_capture', (string)$xml->MsgType);
        $this->assertEquals('123', (string)$xml->OrderId);
        $this->assertEquals(CentinelClient::CURRENCY_US, (string)$xml->CurrencyCode);
        $this->assertEquals('test', (string)$xml->OrderDescription);
        $this->assertEquals('Ground', (string)$xml->ShipMethodName);
        $this->assertEquals('xyz', (string)$xml->TrackingNumber);
        $this->assertEquals(CentinelClient::TYPE_AMAZON, (string)$xml->TransactionType);

        // make sure that the items were added correctly
        $this->assertEquals('ABC', (string)$xml->Item_SKU_1);
        $this->assertEquals('2', (string)$xml->Item_Quantity_1);
        $this->assertEquals('DEF', (string)$xml->Item_SKU_2);
        $this->assertEquals('1', (string)$xml->Item_Quantity_2);

        // make sure the result is created correctly
        $xml = $c->getResult();
        $this->assertInstanceOf('SimpleXMLElement', $xml);
        $this->assertEquals('8604929789808576', (string)$xml->OrderId);
    }
}