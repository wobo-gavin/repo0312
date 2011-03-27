<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\BadResponseException;

/**
 * cURL request exception
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
class CurlException extends BadResponseException
{
    /**
     * @var string
     */
    private $curlError;

    /**
     * Set the cURL error message
     *
     * @param string $error Curl error
     */
    public function setCurlError($error)
    {
        $this->curlError = $error;

        return $this;
    }

    /**
     * Get the associated cURL error message
     *
     * @return string
     */
    public function getCurlError()
    {
        return $this->curlError;
    }
}