<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\CardinalCommerce\Centinel\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\PaymentStatus;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\CentinelClient;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PaymentStatusTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\PaymentStatus
     */
    public function testPaymentStatus()
    {
        $c = new PaymentStatus();
        $this->assertSame($c, $c->setNotificationId('123'));
        $this->assertSame($c, $c->setTransactionType('Ac'));
        
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'PaymentStatusResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($c);

        $xml = new \SimpleXMLElement(trim($c->getRequest()->getPostFields()->get('cmpi_msg')));
        $this->assertEquals('cmpi_payment_status', (string)$xml->MsgType);
        $this->assertEquals('123', (string)$xml->NotificationId);
        $this->assertEquals('Ac', (string)$xml->TransactionType);

        $xml = $c->getResult();
        $this->assertInstanceOf('SimpleXMLElement', $xml);
        $this->assertEquals('OH', (string)$xml->ShippingState);
    }
}