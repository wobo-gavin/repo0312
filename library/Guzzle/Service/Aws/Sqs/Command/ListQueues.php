<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

/**
 * List the queues owned by an account
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ queue_name_prefix doc="String to use for filtering the list results. Only those queues whose name begins with the specified string are returned"
 */
class ListQueues extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest(RequestInterface::GET);
        $this->request->getQuery()->set('Action', 'ListQueues');

        if ($this->get('queue_name_prefix')) {
            $this->request->getQuery()->set('QueueNamePrefix', $this->get('queue_name_prefix'));
        }
    }

    /**
     * Set a prefix that must be present in each of the queue names returned
     * from the request
     *
     * @param string $prefix Queue name prefix
     *
     * @return ListQueues
     */
    public function setQueueNamePrefix($prefix)
    {
        return $this->set('queue_name_prefix', $prefix);
    }

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        parent::process();

        $this->result = array();
        foreach ($this->xmlResult->ListQueuesResult->QueueUrl as $url) {
            $this->result[] = trim($url);
        }
    }

    /**
     * Returns an array of the matching queue names
     *
     * @return array
     */
    public function getResult()
    {
        return parent::getResult();
    }
}