<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\SignatureV2;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\AbstractBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Signature\AbstractSignature;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\SimpleDbClient;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\QueryStringAuthPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\ConcreteDescriptionBuilder;

/**
 * Builder object to build an Amazon SimpleDb /* Replaced /* Replaced /* Replaced client */ */ */
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class SimpleDbBuilder extends AbstractBuilder
{
    const REGION_DEFAULT = 'sdb.amazonaws.com'; // Endpoint located in the US-East (Northern Virginia) Region
    const REGION_US_WEST_1 = 'sdb.us-west-1.amazonaws.com'; // Endpoint located in the US-West (Northern California) Region
    const REGION_EU_WEST_1 = 'sdb.eu-west-1.amazonaws.com'; // Endpoint located in the EU (Ireland) Region
    const REGION_AP_SOUTHEAST_1 = 'sdb.ap-southeast-1.amazonaws.com'; // Endpoint located in the Asia Pacific (Singapore) Region

    /**
     * @var AbstractSignature
     */
    protected $signature;

    /**
     * Build the Amazon SimpleDb /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return SimpleDbClient
     */
    public function build()
    {
        if (!$this->signature) {
            $this->signature = new SignatureV2($this->config->get('access_key_id'), $this->config->get('secret_access_key'));
        }

        $builder = new ConcreteDescriptionBuilder($this->getClass(), $this->config->get('base_url'));
        $serviceDescription = $builder->build();
        $commandFactory = new ConcreteCommandFactory($serviceDescription);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new SimpleDbClient($this->config, $serviceDescription, $commandFactory);
        $/* Replaced /* Replaced /* Replaced client */ */ */->attachPlugin(new QueryStringAuthPlugin($this->signature, $this->config->get('version', '2009-04-15')));
        
        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\SimpleDb\\SimpleDbClient';
    }
}