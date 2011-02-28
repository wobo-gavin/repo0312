<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\AbstractS3BucketCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;

/**
 * Set the notification settings of a bucket
 *
 * @link http://docs.amazonwebservices.com/AmazonS3/latest/API/index.html?RESTBucketPUTnotification.html
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket doc="Bucket to set the notification on" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ notification doc="XML Bucket notification settings" required="true"
 */
class PutBucketNotification extends AbstractS3BucketCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::PUT, $this->get('bucket'));
        $this->request->getQuery()->set('notification', false);
        $this->request->setBody(EntityBody::factory($this->get('notification')));
    }

    /**
     * Set the bucket notification settings
     *
     * @param string|SimpleXMLElement $notification Notification settings to set
     *
     * @return PutBucketNotification
     */
    public function setNotification($notification)
    {
        if ($notification instanceof \SimpleXMLElement) {
            $xml = $notification->asXML();
            $xml = implode("\n", array_slice(explode("\n", $xml), 1));
        } else {
            $xml = $notification;
        }

        return $this->set('notification', $xml);
    }
}