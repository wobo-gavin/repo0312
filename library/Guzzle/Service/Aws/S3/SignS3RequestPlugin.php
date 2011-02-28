<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Subject\SubjectMediator;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\AbstractPlugin;

/**
 * Plugin to sign requests for Amazon S3 before sending
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class SignS3RequestPlugin extends AbstractPlugin
{
    /**
     * @var S3Signature
     */
    private $signature;

    /**
     * Construct a new request signing plugin
     *
     * @param S3Signature $signature Signature object used to sign requests
     */
    public function __construct(S3Signature $signature)
    {
        $this->signature = $signature;
    }

    /**
     * Get the signature object used to sign requests
     *
     * @return S3Signature
     */
    public function getSignature()
    {
        return $this->signature;
    }
    
    /**
     * {@inheritdoc}
     */
    public function update(SubjectMediator $subject)
    {
        if ($subject->getState() == 'request.create') {
            $subject->getContext()->getPrepareChain()->addFilter(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Filter\AddAuthHeader(array(
                'signature' => $this->signature
            )));
        }
    }
}