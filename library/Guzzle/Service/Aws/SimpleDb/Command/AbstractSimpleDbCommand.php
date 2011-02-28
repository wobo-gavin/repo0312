<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand;

/**
 * Delete an Amazon SimpleDB domain
 *
 * @link http://docs.amazonwebservices.com/AmazonSimpleDB/latest/DeveloperGuide/index.html?SDB_API_DeleteDomain.html
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
abstract class AbstractSimpleDbCommand extends AbstractCommand
{
    /**
     * @var string The action to take on the API
     */
    protected $action;

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        $this->result = new \SimpleXMLElement($this->getResponse()->getBody(true));
    }

    /**
     * Returns a SimpleXMLElement
     *
     * @return \SimpleXMLElement
     */
    public function getResult()
    {
        return parent::getResult();
    }
}