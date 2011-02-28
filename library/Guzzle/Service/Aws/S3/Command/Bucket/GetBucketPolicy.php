<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\AbstractS3BucketCommand;

/**
 * Get the policy of a bucket
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @link   http://docs.amazonwebservices.com/AmazonS3/latest/API/index.html?RESTBucketGETpolicy.html
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket required="true" doc="Bucket for which to retrieve the policy"
 */
class GetBucketPolicy extends AbstractS3BucketCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::GET, $this->get('bucket'));
        $this->request->getQuery()->set('policy', false);
    }

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        $this->result = json_decode(trim($this->getResponse()->getBody(true)), true);
    }

    /**
     * Returns the policy of the bucket as an array of data
     *
     * @return array
     */
    public function getResult()
    {
        return parent::getResult();
    }
}