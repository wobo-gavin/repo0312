<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command;

/**
 * Abstract Amazon SQS command which uses a queue URL
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
abstract class AbstractQueueUrlCommand extends AbstractCommand
{
    /**
     * @var string Action to take on the service
     */
    protected $action;

    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
        $this->request->setUrl($this->get('queue_url') . '?Action=' . $this->action);
    }

    /**
     * Set the queue URL
     *
     * @param string $url The full SQS queue URL
     *
     * @return AbstractQueueUrlCommand
     */
    public function setQueueUrl($url)
    {
        return $this->set('queue_url', $url);
    }
}