<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;

/**
 * The HEAD operation retrieves metadata from an object without returning the
 * object itself. This operation is useful if you're only interested in an
 * object's metadata. To use HEAD, you must have READ access to the object.
 * If READ access is granted to the anonymous user, you can request the
 * object's metadata without an authorization header.
 *
 * A HEAD request has the same options as a GET operation on an object. The
 * response is identical to the GET response, except that there is no response
 * body.
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket doc="Bucket where the object is stored" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ key doc="Object key" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ headers doc="Headers to set on the request" type="class:/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ range doc="Downloads the specified range of an object"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ if_modified_since" doc="Return the object only if it has been modified since the specified time, otherwise return a 304 (not modified)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ if_unmodified_since doc="Return the object only if it has not been modified since the specified time, otherwise return a 412 (precondition failed)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ if_match doc="Return the object only if its entity tag (ETag) is the same as the one specified, otherwise return a 412 (precondition failed)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ if_none_match doc="Return the object only if its entity tag (ETag) is different from the one specified, otherwise return a 304 (not modified)."
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class HeadObject extends AbstractRequestObject
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::HEAD, $this->get('bucket'), $this->get('key'));
        $this->applyDefaults($this->request);
    }
}