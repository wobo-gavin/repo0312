<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\SimpleDbException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\PutAttributes;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command\BatchPutAttributes;

/**
 * Allows logging to Amazon SimpleDB.
 *
 * Log messages will be queued and sent in a single BatchPutAttributes command
 * when the maximum number of log messages have been queued, flush() is called,
 * and on the __destruct() method.
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */-project.org>
 */
class SimpleDbLogAdapter extends AbstractQueuedLogAdapter
{
    const HOST_COLUMN = 'host';
    const CATEGORY_COLUMN = 'category';
    const TIME_COLUMN = 'time';
    const MESSAGE_COLUMN = 'msg';
    const PRIORITY_COLUMN = 'priority';

    /**
     * {@inheritdoc}
     */
    protected $className = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\SimpleDbClient';

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        if (!$this->config->get('domain')) {
            throw new LogAdapterException('A domain must be supplied in the $config settings of the ' . __CLASS__);
        }
    }

    /**
     * Flush any queued log messages and send them to Amazon SimpleDB
     *
     * @return integer Returns the number of queued messages that were sent
     * @throws SimpleDbException on SimpleDB error
     */
    public function flush()
    {
        $total = count($this->queued);

        if ($total) {
            $command = new BatchPutAttributes();
            $command->setDomain($this->config->get('domain'))
                    ->addItems($this->queued);
            
            $this->log->execute($command);
        }

        $this->queued = array();

        return $total;
    }

    /**
     * {@inheritdoc}
     *
     * @throws LogAdapterException
     */
    protected function logMessage($message, $priority = self::INFO, $category = null, $host = null)
    {
        $itemName = uniqid('log_');

        $data = array(
            self::MESSAGE_COLUMN => $message,
            self::PRIORITY_COLUMN => $priority,
            self::TIME_COLUMN => date('c') // ISO 8601
        );

        if ($category) {
            $data[self::CATEGORY_COLUMN] = $category;
        }

        if ($host) {
            $data[self::HOST_COLUMN] = $host;
        }

        ksort($data);

        $this->queued[$itemName] = $data;
        if ($this->config->get('implicit_flush') || count($this->queued) >= $this->maxQueueSize) {
            $this->flush();
        }
    }
}