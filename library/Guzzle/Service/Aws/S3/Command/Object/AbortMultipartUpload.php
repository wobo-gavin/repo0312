<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\AbstractS3BucketCommand;

/**
 * Aborts a multipart upload to an object
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket doc="Bucket that contains the object" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ key doc="Key of the object to abort the upload" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ upload_id doc="Multipart upload ID" required="true"
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AbortMultipartUpload extends AbstractS3BucketCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::DELETE, $this->get('bucket'), $this->get('key'));
        $this->request->getQuery()->set('uploadId', $this->get('upload_id'));
    }

    /**
     * Set the key of the object
     *
     * @param string $key The key or name of the object
     *
     * @return AbortMultipartUpload
     */
    public function setKey($key)
    {
        return $this->set('key', $key);
    }

    /**
     * Set the multipart upload ID
     *
     * @param string $uploadId
     *
     * @return AbortMultipartUpload
     */
    public function setUploadId($uploadId)
    {
        return $this->set('upload_id', $uploadId);
    }
}