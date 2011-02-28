<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\AbstractRequestObject;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\Acl;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;

/**
 * Set the ACL of an object or bucket
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket doc="Bucket" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ acl doc="ACL to set" required="true" type="class:/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\Acl"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ key doc="Object key (optional)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ version_id doc="Version ID to set"
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PutAcl extends AbstractRequestObject
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::PUT, $this->get('bucket'), $this->get('key'));
        $this->request->getQuery()->set('acl', false);
        $this->request->setBody(EntityBody::factory((string)$this->get('acl')));

        // Add the versionId if setting an ACL of an object
        if ($this->get('key') && $this->get('version_id')) {
            $this->request->getQuery()->set('versionId', $this->get('version_id'));
        }
    }

    /**
     * Set the ACL of the PutAcl command
     *
     * @param Acl $acl The ACL to set
     *
     * @return PutAcl
     */
    public function setAcl(Acl $acl)
    {
        return $this->set('acl', $acl);
    }

    /**
     * Set the version ID of the object to set the ACL
     *
     * @param string $versionId
     *
     * @return PutAcl
     */
    public function setVersionId($versionId)
    {
        return $this->set('version_id', $versionId);
    }
}