<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Exception;

/**
 * Get an object from a bucket
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket doc="Bucket where the object is stored" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ key doc="Object key" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ headers doc="Headers to set on the request" type="class:/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ body doc="Entity body to store the response body" type="class:/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ range doc="Downloads the specified range of an object"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ if_modified_since" doc="Return the object only if it has been modified since the specified time, otherwise return a 304 (not modified)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ if_unmodified_since doc="Return the object only if it has not been modified since the specified time, otherwise return a 412 (precondition failed)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ if_match doc="Return the object only if its entity tag (ETag) is the same as the one specified, otherwise return a 412 (precondition failed)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ if_none_match doc="Return the object only if its entity tag (ETag) is different from the one specified, otherwise return a 304 (not modified)."
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class GetObject extends AbstractRequestObject
{
    /**
     * @var bool Whether or not to send a checksum with the GET
     */
    protected $validateChecksum = true;

    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::GET, $this->get('bucket'), $this->get('key'));
        $this->applyDefaults($this->request);

        if ($this->get('torrent')) {
            $this->request->getQuery()->add('torrent', null);
        }

        if ($this->hasKey('body')) {
            $this->request->setResponseBody($this->get('body'));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        $this->result = $this->getResponse();

        // Validate the checksum
        if ($this->validateChecksum && $this->request->isResponseBodyRepeatable()) {
            $expected = trim(str_replace('"', '', $this->getResponse()->getEtag()));
            $received = $this->getResponse()->getBody()->getContentMd5();
            if (strcmp($expected, $received)) {
                throw new S3Exception('Checksum mismatch when downloading object: expected ' . $expected . ', recieved ' . $received);
            }
        }
    }

    /**
     * Disable checksum validation when the object has been retrieved
     *
     * @return PutObject
     */
    public function disableChecksumValidation()
    {
        $this->validateChecksum = false;
        
        return $this;
    }

    /**
     * Set the EntityBody that will hold the response body.  This is useful for
     * downloading to custom destinations other than the default temp stream.
     * This can be used for downloading a file directly to a locally stored file.
     *
     * @param EntityBody $body The body object to download the object body
     *
     * @return GetObject
     */
    public function setResponseBody(EntityBody $body)
    {
        return $this->set('body', $body);
    }

    /**
     * Get the object as a torrent file
     *
     * @param bool $getAsTorrent Set to TRUE to GET the object as a torrent file
     *
     * @return GetObject
     */
    public function setTorrent($getAsTorrent)
    {
        return $this->set('torrent', $getAsTorrent);
    }
}