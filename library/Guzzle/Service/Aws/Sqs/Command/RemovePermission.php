<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command;

/**
 * The RemovePermission action revokes any permissions in the queue policy that
 * matches the Label parameter. Only the owner of the queue can remove
 * permissions. 
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ queue_url required="true" doc="URL of the queue"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ label required="true" doc="The identfication of the permission you want to remove. This is the label you added in AddPermission."
 */
class RemovePermission extends AbstractQueueUrlCommand
{
    protected $action = 'RemovePermission';

    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        parent::build();

        $this->request->getQuery()->set('Label', $this->get('label'));
    }

    /**
     * Set the identfication of the permission you want to remove. This is the
     * label you added in AddPermission.
     *
     * @param string $label Label to revoke
     *
     * @return RemovePermission
     */
    public function setLabel($label)
    {
        return $this->set('label', $label);
    }
}