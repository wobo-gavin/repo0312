<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter;

/**
 * Adapts the Zend_Log class to the /* Replaced /* Replaced /* Replaced Guzzle */ */ */ framework
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ZendLogAdapter extends AbstractLogAdapter
{
    /**
     * {@inheritdoc}
     */
    protected $className = 'Zend_Log';

    /**
     * {@inheritdoc}
     */
    protected function logMessage($message, $priority = self::INFO, $category = null, $host = null)
    {
        $compiledMessage = '';
        if ($host) {
            $compiledMessage .= "[{$host}] ";
        }
        if ($category) {
            $compiledMessage .= "[{$category}] ";
        }
        $compiledMessage .= $message;
        
        $this->log->log($compiledMessage, $priority);
    }
}