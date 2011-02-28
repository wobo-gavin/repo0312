<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command;

/**
 * Delete a queue
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ queue_url required="true" doc="URL of the queue to delete"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ receipt_handle required="true" doc="The receipt handle associated with the message you want to delete."
 */
class DeleteMessage extends AbstractQueueUrlCommand
{
    protected $action = 'DeleteMessage';

    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        parent::build();

        $this->request->getQuery()->set('ReceiptHandle', $this->get('receipt_handle'));
    }

    /**
     * Set the receipt handle associated with the message you want to delete.
     *
     * @param string $receiptHandle Receipt handle
     *
     * @return DeleteMessage
     */
    public function setReceiptHandle($receiptHandle)
    {
        return $this->set('receipt_handle', $receiptHandle);
    }
}