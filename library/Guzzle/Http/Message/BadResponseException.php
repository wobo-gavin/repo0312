<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message;

/**
 * Http request exception thrown when a bad response is received
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
class BadResponseException extends RequestException
{
    /**
     * @var Response
     */
    private $response;

    /**
     * Set the response that caused the exception
     *
     * @param Response $response Response to set
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Get the response that caused the exception
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}