<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\CardinalCommerce\Centinel\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Void;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\CentinelClient;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class VoidTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Void
     */
    public function testVoid()
    {
        $c = new Void();
        $this->assertSame($c, $c->setTransactionType(CentinelClient::TYPE_AMAZON));
        $this->assertSame($c, $c->setOrderDescription('desc'));
        $this->assertSame($c, $c->setOrderId('123'));
        $this->assertSame($c, $c->setReason('Fraud'));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'VoidResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($c);

        // Validate the XML message
        $xml = new \SimpleXMLElement(trim($c->getRequest()->getPostFields()->get('cmpi_msg')));
        $this->assertEquals('desc', (string)$xml->OrderDescription);
        $this->assertEquals('123', (string)$xml->OrderId);
        $this->assertEquals('Fraud', (string)$xml->Reason);
        $this->assertEquals('Ac', (string)$xml->TransactionType);

        // Validate the response
        $xml = $c->getResult();
        $this->assertInstanceOf('SimpleXMLElement', $xml);
        $this->assertEquals('Invalid void request: You can\'t void a charged order', (string)$xml->ReasonDesc);
    }
}