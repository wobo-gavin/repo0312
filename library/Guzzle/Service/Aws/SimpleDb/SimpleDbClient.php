<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\AbstractClient;

/**
 * Client for interacting with Amazon SimpleDb
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ access_key_id required="true" doc="AWS Access Key ID"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ secret_access_key required="true" doc="AWS Secret Access Key"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ protocol required="true" default="http" doc="Protocol to use with requests (http or https)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ region required="true" default="sdb.amazonaws.com" doc="Amazon SimpleDB Region endpoint"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ base_url required="true" default="{{ protocol }}://{{ region }}/" doc="SimpleDB service base URL"
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ cache.key_filter static="query=Timestamp, Signature"
 */
class SimpleDbClient extends AbstractClient
{
}