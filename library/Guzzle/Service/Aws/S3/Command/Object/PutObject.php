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
 * PUT an object to Amazon S3
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ key doc="Object key" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket doc="Bucket that contains the object" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ body doc="Body to send to S3" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ headers doc="Headers to set on the request" type="class:/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ acl doc="Canned ACL to set on the object"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ storage_class doc="Use STANDARD or REDUCED_REDUNDANCY storage"
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PutObject extends AbstractRequestObjectPut
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
        $this->applyDefaults($this->request);
        
        $this->request->setBody($this->get('body'));

        // Add the checksum to the PUT
        if ($this->validateChecksum) {
            $this->request->setHeader('Content-MD5', $this->get('body')->getContentMd5());
        }
    }

    /**
     * Disable checksum validation when sending the object.
     *
     * Calling this method will prevent a Content-MD5 header from being sent in
     * the request.
     *
     * @return PutObject
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
     * @return PutObject
     */
    public function setBody($body)
    {
        return $this->set('body', EntityBody::factory($body));
    }
}