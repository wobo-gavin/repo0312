<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class SignS3RequestPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\SignS3RequestPlugin
     */
    public function testSignsS3Requests()
    {
        $signature = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Signature('a', 'b');
        $plugin = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\SignS3RequestPlugin($signature);
        $this->assertSame($signature, $plugin->getSignature());

        $request = \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::getInstance()->newRequest('GET', 'http://www.test.com/');

        $mediator = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Subject\SubjectMediator($request);
        $mediator->notify('request.create', $request);

        $plugin->update($mediator);
        $this->assertTrue($request->getPrepareChain()->hasFilter('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\Filter\\AddAuthHeader'));
    }
}