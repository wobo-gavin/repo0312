<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Mws;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\AbstractBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\ConcreteDescriptionBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV2;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\QueryStringAuthPlugin;

/**
 * @author Harold Asbridge <harold@shoebacca.com>
 */
class MwsBuilder extends AbstractBuilder
{
    protected $signature;
    protected $endpoint;

    const VERSION = '2009-01-01';

    /**
     * Build /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return MwsClient
     */
    public function build()
    {
        if (!$this->signature) {
            $this->signature = new SignatureV2($this->config->get('access_key_id'), $this->config->get('secret_access_key'));
        }

        $builder = new ConcreteDescriptionBuilder($this->getClass(), $this->config->get('base_url'));
        $serviceDescription = $builder->build();
        $commandFactory = new ConcreteCommandFactory($serviceDescription);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new MwsClient($this->config, $serviceDescription, $commandFactory);
        $/* Replaced /* Replaced /* Replaced client */ */ */->attachPlugin(new QueryStringAuthPlugin($this->signature, $this->config->get('version', self::VERSION)));

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\Mws\\MwsClient';
    }
}