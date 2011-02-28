<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\AbstractS3BucketCommand;

/**
 * Delete a bucket policy
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @link   http://docs.amazonwebservices.com/AmazonS3/latest/API/index.html?RESTBucketDELETEpolicy.html
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket required="true" doc="Name of the bucket"
 */
class DeleteBucketPolicy extends AbstractS3BucketCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::DELETE, $this->get('bucket'));
        $this->request->getQuery()->set('policy', false);
    }
}