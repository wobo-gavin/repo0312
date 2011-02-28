<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\AbstractS3BucketCommand;

/**
 * Get the request payment configuration of  a bucket.
 *
 * @link http://docs.amazonwebservices.com/AmazonS3/latest/API/index.html?RESTrequestPaymentGET.html
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket required="true"
 */
class GetBucketRequestPayment extends AbstractS3BucketCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::GET, $this->get('bucket'));
        $this->request->getQuery()->set('requestPayment', false);
    }

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        $xml = new \SimpleXMLElement($this->getResponse()->getBody(true));
        $this->result = (string)$xml->Payer;
    }

    /**
     * Returns the party responsible for paying for bucket requests
     *
     * @return string
     */
    public function getResult()
    {
        return parent::getResult();
    }
}