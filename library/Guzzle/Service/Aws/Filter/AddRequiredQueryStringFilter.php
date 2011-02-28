<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Filter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\AbstractFilter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV1;

/**
 * Add a query string signature to a request
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AddRequiredQueryStringFilter extends AbstractFilter
{
    /**
     * Add required query string parameters to the request
     *
     * @return bool Returns TRUE on success or FALSE on failure
     */
    protected function filterCommand($command)
    {
        // @codeCoverageIgnoreStart
        if (!($command instanceof RequestInterface)) {
            return false;
        }
        // @codeCoverageIgnoreEnd

        $signature = $this->get('signature');

        // @codeCoverageIgnoreStart
        if (!$signature) {
            return false;
        }
        // @codeCoverageIgnoreEnd
        
        $qs = $command->getQuery();

        // Add required parameters to the request
        if (!$qs->hasKey('Timestamp')) {
            $qs->set('Timestamp', gmdate('c'));
        }

        if (!$qs->hasKey('Version')) {
            $qs->set('Version', $this->get('version'));
        }

        if (!$qs->hasKey('SignatureVersion')) {
            $qs->set('SignatureVersion', $signature->getVersion());
        }

        // Signature V2 and onward functionality
        if ((int)$signature->getVersion() > 1 && !$qs->hasKey('SignatureMethod')) {
            $qs->set('SignatureMethod', $signature->getAwsHashingAlgorithm());
        }

        if (!$qs->hasKey('AWSAccessKeyId')) {
            $qs->set('AWSAccessKeyId', $signature->getAccessKeyId());
        }

        return true;
    }
}