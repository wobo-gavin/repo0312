<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\ClosureFilter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\AbstractBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Filter\DevPayTokenHeaders;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\ConcreteDescriptionBuilder;

/**
 * Builder object to build an Amazon S3 /* Replaced /* Replaced /* Replaced client */ */ */
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class S3Builder extends AbstractBuilder
{
    const REGION_DEFAULT = 's3.amazonaws.com';
    const REGION_US_WEST_1 = 's3-us-west-1.amazonaws.com';
    const REGION_AP_SOUTHEAST_1 = 's3-ap-southeast-1.amazonaws.com';
    const REGION_EU = 's3-eu-west-1.amazonaws.com';

    /**
     * Build the Amazon S3 /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return S3Client
     */
    public function build()
    {
        $builder = new ConcreteDescriptionBuilder($this->getClass(), $this->config->get('base_url'));
        $serviceDescription = $builder->build();
        $commandFactory = new ConcreteCommandFactory($serviceDescription);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new S3Client($this->config, $serviceDescription, $commandFactory);

        // If an access key and secret access key were provided, then the /* Replaced /* Replaced /* Replaced client */ */ */
        // requests will be authenticated
        if ($this->config->get('access_key_id') && $this->config->get('secret_access_key')) {
            if (!$this->signature) {
                $this->signature = new S3Signature($this->config->get('access_key_id'), $this->config->get('secret_access_key'));
            }
            $/* Replaced /* Replaced /* Replaced client */ */ */->attachPlugin(new SignS3RequestPlugin($this->signature));
        }

        // If Amazon DevPay tokens were provided, then add a DevPay filter
        if ($this->config->get('devpay_user_token') && $this->config->get('devpay_product_token')) {
            $config = $this->config;;
            $/* Replaced /* Replaced /* Replaced client */ */ */->getCreateRequestChain()->addFilter(new ClosureFilter(function($request) use ($config) {
                $request->getPrepareChain()->addFilter(new DevPayTokenHeaders(array(
                    'user_token' => $config->get('devpay_user_token'),
                    'product_token' => $config->get('devpay_product_token'),
                )));
            }));
        }

        // Create the actual /* Replaced /* Replaced /* Replaced client */ */ */ object
        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    /**
     * Set DevPay tokens to use with Amazon S3 requests
     *
     * @param string $userToken The Amazon DevPay user token
     * @param string $productToken The Amazon DevPay product token
     *
     * @return S3Builder
     */
    public function setDevPayTokens($userToken, $productToken)
    {
        $this->config->set('devpay_user_token', $userToken)
                      ->set('devpay_product_token', $productToken);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\S3Client';
    }
}