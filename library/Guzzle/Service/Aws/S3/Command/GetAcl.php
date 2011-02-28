<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\Acl;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3Exception;

/**
 * Get the ACL of an object.
 *
 * From the docs:  This implementation of the GET operation uses the acl
 * sub-resource to return  the access control list (ACL) of a bucket or object.
 * To use GET to return  the ACL of the bucket, you must have READ_ACP access
 * to the bucket. If  READ_ACP permission is granted to the anonymous user, you
 * can return the ACL of the bucket without using an authorization header.
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket doc="Bucket" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ key doc="Key of the object"
 *
 * @link http://docs.amazonwebservices.com/AmazonS3/latest/API/index.html?RESTBucketGETacl.html
 * @link http://docs.amazonwebservices.com/AmazonS3/latest/API/index.html?RESTObjectGETacl.html
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class GetAcl extends AbstractS3BucketCommand
{
    /**
     * {@inheritdoc}
     *
     * @throws S3Exception
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::GET, $this->get('bucket'), $this->get('key'));
        $this->request->getQuery()->set('acl', false);
    }

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        $this->result = new Acl(new \SimpleXMLElement($this->getResponse()->getBody(true)));
    }

    /**
     * Returns an ACL model
     *
     * @return Acl
     */
    public function getResult()
    {
        return parent::getResult();
    }

    /**
     * Set the key of the object.  If no key is specified, you will get the
     * ACL of a bucket.
     *
     * @param string $key The key or name of the object
     *
     * @return GetObject
     */
    public function setKey($key)
    {
        return $this->set('key', $key);
    }
}