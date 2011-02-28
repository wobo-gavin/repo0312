<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\AbstractClient;

/**
 * Client for interacting with Amazon SQS
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ access_key_id required="true" doc="AWS Access Key ID"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ secret_access_key required="true" doc="AWS Secret Access Key"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ protocol required="true" default="http" doc="Protocol to use with requests (http or https)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ region required="true" default="sqs.us-east-1.amazonaws.com" doc="Amazon SQS Region endpoint"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ base_url required="true" default="{{ protocol }}://{{ region }}/" doc="SQS service base URL"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ version required="true" default="2009-02-01"
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ cache.key_filter static="query=Timestamp, Signature"
 */
class SqsClient extends AbstractClient
{
}