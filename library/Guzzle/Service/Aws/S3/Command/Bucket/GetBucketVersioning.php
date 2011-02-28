<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\AbstractS3BucketCommand;

/**
 * This implementation of the GET operation uses the versioning sub-resource to
 * return the versioning state of a bucket. To retrieve the versioning state of
 * a bucket, you must be the bucket owner.
 *
 * This implementation also returns the MFA Delete status of the versioning
 * state, i.e., if the MFA Delete status is enabled, the bucket owner must use
 * an  authentication device to change the versioning state of the bucket.
 *
 * @link http://docs.amazonwebservices.com/AmazonS3/latest/API/index.html?RESTBucketGETversioningStatus.html
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket required="true"
 */
class GetBucketVersioning extends AbstractS3BucketCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::GET, $this->get('bucket'));
        $this->request->getQuery()->set('versioning', false);
    }

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        $this->result = new \SimpleXMLElement($this->getResponse()->getBody(true));
    }
}