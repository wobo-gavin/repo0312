<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Subject\Observer;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

/**
 * Abstract AWS Client
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
abstract class AbstractClient extends Client
{
    /**
     * Get the AWS Access Key ID
     *
     * @return string
     */
    public function getAccessKeyId()
    {
        return $this->config->get('access_key_id');
    }

    /**
     * Get the AWS Secret Access Key
     *
     * @return string
     */
    public function getSecretAccessKey()
    {
        return $this->config->get('secret_access_key');
    }
}