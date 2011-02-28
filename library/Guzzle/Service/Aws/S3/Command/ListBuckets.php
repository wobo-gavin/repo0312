<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\BucketList;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand;

/**
 * List the buckets in your account.
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ListBuckets extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::GET);
    }

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        if ($this->getResponse()->isSuccessful()) {
            $this->result = new BucketList(new \SimpleXMLElement($this->getResponse()->getBody(true)));
        }
    }

    /**
     * Returns an BucketList model
     *
     * @return BucketList
     */
    public function getResult()
    {
        return parent::getResult();
    }
}