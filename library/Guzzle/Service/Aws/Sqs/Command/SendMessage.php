<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command;

/**
 * Send a message to a queue
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ queue_url required="true" doc="URL of the queue to delete"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ message required="true" doc="Message body"
 */
class SendMessage extends AbstractQueueUrlCommand
{
    protected $action = 'SendMessage';

    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        parent::build();

        $this->request->getQuery()->set('Message', $this->get('message'));
    }

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        parent::process();

        $this->result = array(
            'message_id' => trim((string)$this->xmlResult->SendMessageResult->MessageId),
            'md5' => trim((string)$this->xmlResult->SendMessageResult->MD5OfMessageBody),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return array Returns an associative array of data containing the following:
     *      message_id => Message ID of the sent message
     *      md5 => MD5 of the message
     */
    public function getResult()
    {
        return parent::getResult();
    }

    /**
     * Set the message body of the message
     *
     * @param string $message Message to send
     *
     * @return SendMessage
     */
    public function setMessage($message)
    {
        return $this->set('message', $message);
    }
}