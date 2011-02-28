<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\CardinalCommerce\Centinel\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\AddOrderNumber;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\CentinelClient;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AddOrderNumberTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\AddOrderNumber
     */
    public function testAddOrderNumber()
    {
        $c = new AddOrderNumber();
        $this->assertSame($c, $c->setOrderId('8604929789808576'));
        $this->assertSame($c, $c->setOrderNumber('abc'));
        $this->assertSame($c, $c->setTransactionType(CentinelClient::TYPE_AMAZON));
        
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'AddOrderNumberResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($c);
        $xml = new \SimpleXMLElement(trim($c->getRequest()->getPostFields()->get('cmpi_msg')));

        $this->assertEquals('cmpi_add_order_number', (string)$xml->MsgType);
        $this->assertEquals('abc', (string)$xml->OrderNumber);
        $this->assertEquals('8604929789808576', (string)$xml->OrderId);
        $this->assertEquals('Ac', (string)$xml->TransactionType);

        $xml = $c->getResult();
        $this->assertInstanceOf('SimpleXMLElement', $xml);
        $this->assertEquals('8604929789808576', (string)$xml->OrderId);
    }
}