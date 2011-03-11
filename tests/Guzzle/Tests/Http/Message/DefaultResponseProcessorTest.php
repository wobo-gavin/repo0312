<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\BadResponseException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class DefaultResponseProcessorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\DefaultResponseProcessor
     */
    public function testCurlErrorsAreCaught()
    {
        $this->getServer()->enqueue("HTTP/1.1 404 Not found\r\nContent-Length: 0\r\n\r\n");

        try {
            $request = RequestFactory::get($this->getServer()->getUrl() . 'index.html');
            $response = $request->send();
            $this->fail('Request did not receive a 404 response');
        } catch (BadResponseException $e) {
            $this->assertContains('Unsuccessful response ', $e->getMessage());
            $this->assertContains('[status code] 404 | [reason phrase] Not found | [url] http://127.0.0.1:8124/index.html | [request] GET /index.html HTTP/1.1', $e->getMessage());
            $this->assertContains('Host: 127.0.0.1:8124', $e->getMessage());
            $this->assertContains(" | [response] HTTP/1.1 404 Not found", $e->getMessage());
        }
    }
}