<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;

/**
 * This operation uploads a part in a multipart upload.
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ key doc="Object key" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket doc="Bucket that contains the object" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ body doc="Body to send to S3" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ upload_id doc="Upload ID" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ part_number doc="Part number" required="true"
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @link   http://docs.amazonwebservices.com/AmazonS3/latest/API/index.html?mpUploadUploadPart.html
 */
class UploadPart extends AbstractRequestObject
{
    /**
     * @var bool Whether or not to send a checksum with the PUT
     */
    protected $validateChecksum = true;

    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::PUT, $this->get('bucket'), $this->get('key'));
        $this->request->getQuery()->set('partNumber', $this->get('part_number'))
                                         ->set('uploadId', $this->get('upload_id'));
        $this->applyDefaults($this->request);

        $this->request->setBody($this->get('body'));

        // Add the checksum to the PUT
        if ($this->validateChecksum) {
            $this->request->setHeader('Content-MD5', $this->get('body')->getContentMd5());
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        $this->result = $this->getResponse()->getEtag();
    }

    /**
     * Get the ETag response header
     *
     * @return string
     */
    public function getResult()
    {
        return parent::getResult();
    }

    /**
     * Disable checksum validation when sending the object.
     *
     * Calling this method will prevent a Content-MD5 header from being sent in
     * the request.
     *
     * @return UploadPart
     */
    public function disableChecksumValidation()
    {
        $this->validateChecksum = false;

        return $this;
    }

    /**
     * Set the body of the object
     *
     * @param string|EntityBody $body Body of the object to set
     *
     * @return UploadPart
     */
    public function setBody($body)
    {
        return $this->set('body', EntityBody::factory($body));
    }

    /**
     * Set the upload ID of the request
     *
     * @param string $uploadId Upload ID
     *
     * @return UploadPart
     */
    public function setUploadId($uploadId)
    {
        return $this->set('upload_id', $uploadId);
    }

    /**
     * Set the part number of the request
     *
     * @param string $partNumber Part number
     *
     * @return UploadPart
     */
    public function setPartNumber($partNumber)
    {
        return $this->set('part_number', $partNumber);
    }
}