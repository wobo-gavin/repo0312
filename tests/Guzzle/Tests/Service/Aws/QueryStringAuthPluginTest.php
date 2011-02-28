<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Subject\SubjectMediator;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV2;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\QueryStringAuthPlugin;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class QueryStringAuthPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\QueryStringAuthPlugin
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Filter\AddRequiredQueryStringFilter
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Filter\QueryStringSignatureFilter
     */
    public function testAddsQueryStringAuth()
    {
        $signature = new SignatureV2('a', 'b');
        
        $plugin = new QueryStringAuthPlugin($signature, '2009-04-15');
        $this->assertSame($signature, $plugin->getSignature());
        $this->assertEquals('2009-04-15', $plugin->getApiVersion());

        $request = RequestFactory::getInstance()->newRequest('GET', 'http://www.test.com/');

        $mediator = new SubjectMediator($request);
        $mediator->notify('request.create', $request);

        $plugin->update($mediator);
        $this->assertTrue($request->getPrepareChain()->hasFilter('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\Filter\\AddRequiredQueryStringFilter'));
        $this->assertTrue($request->getPrepareChain()->hasFilter('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\Filter\\QueryStringSignatureFilter'));

        $request->getPrepareChain()->process($request);
        $qs = $request->getQuery();
        $this->assertTrue($qs->hasKey('Timestamp'));
        $this->assertEquals('2009-04-15', $qs->get('Version'));
        $this->assertEquals('2', $qs->get('SignatureVersion'));
        $this->assertEquals('HmacSHA256', $qs->get('SignatureMethod'));
        $this->assertEquals('a', $qs->get('AWSAccessKeyId'));
    }
}