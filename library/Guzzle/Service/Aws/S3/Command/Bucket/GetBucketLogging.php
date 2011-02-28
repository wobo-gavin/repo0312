<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\BucketLoggingStatus;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\AbstractS3BucketCommand;

/**
 * Get the logging settings of a bucket
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket required="true"
 */
class GetBucketLogging extends AbstractS3BucketCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::GET, $this->get('bucket'));
        $this->request->getQuery()->set('logging', false);
    }

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        $this->result = new BucketLoggingStatus(new \SimpleXMLElement($this->getResponse()->getBody(true)));
    }

    /**
     * Returns an object containing information regarding the bucket logging
     * settings of the bucket
     *
     * @return BucketLoggingStatus
     */
    public function getResult()
    {
        return parent::getResult();
    }
}