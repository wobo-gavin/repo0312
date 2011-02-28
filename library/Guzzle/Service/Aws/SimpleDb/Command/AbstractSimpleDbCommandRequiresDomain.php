<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

/**
 * Abstract class for SimpleDB commands that require a domain to be set
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AbstractSimpleDbCommandRequiresDomain extends AbstractSimpleDbCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest(RequestInterface::GET);
        $this->request->getQuery()
            ->set('Action', $this->action)
            ->set('DomainName', $this->get('domain'));
    }

    /**
     * Set the domain
     *
     * @param string $domain The domain to get metadata about
     *
     * @return AbstractSimpleDbCommandRequiresDomain
     */
    public function setDomain($key)
    {
        return $this->set('domain', $key);
    }
}