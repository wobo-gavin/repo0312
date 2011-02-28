<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\CardinalCommerce\Centinel\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Authenticate;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\CentinelClient;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AuthenticateTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CardinalCommerce\Centinel\Command\Authenticate
     */
    public function testAuthenticate()
    {
        $auth = new Authenticate();
        $auth->setTransactionType(CentinelClient::TYPE_CREDIT_CARD);
        $auth->setParEsPayload('test');
        $auth->setOrderId('123');

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.centinel');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'AuthenticateResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($auth);

        $this->assertContains('cmpi_authenticate', (string)$auth->getRequest());
        
        $xml = new \SimpleXMLElement(trim($auth->getRequest()->getPostFields()->get('cmpi_msg')));
        $this->assertNotEmpty((string)$xml->PAResPayload);

        $xml = $auth->getResult();
        $this->assertInstanceOf('SimpleXMLElement', $xml);
        $this->assertEquals('k4Vf36ijnJX54kwHQNqUr8/ruvs=', (string)$xml->Xid);
    }
}