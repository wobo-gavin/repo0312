<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

/**
 * Mock Command
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ test default="123" required="true" doc="Test argument"
 */
class MockCommand extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
    }

    /**
     * Set whether or not the command can be batched
     *
     * @param bool $canBatch
     *
     * @return MockCommand
     */
    public function setCanBatch($canBatch)
    {
        $this->canBatch = $canBatch;

        return $this;
    }
}