<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\BadResponseException;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ExceptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestException
     */
    public function testRequestException()
    {
        $e = new RequestException('Message');
        $request = new Request('GET', 'http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/');
        $e->setRequest($request);
        $this->assertEquals($request, $e->getRequest());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\BadResponseException
     */
    public function testBadResponseException()
    {
        $e = new BadResponseException('Message');
        $response = new Response(200);
        $e->setResponse($response);
        $this->assertEquals($response, $e->getResponse());
    }
}