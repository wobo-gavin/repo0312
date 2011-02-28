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
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\ListPartsIterator;

/**
 * This operation lists the parts that have been uploaded for a specific
 * multipart upload.
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ bucket doc="Bucket where the object is stored" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ key doc="Object key" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ upload_id doc="Upload ID of the upload" required="true"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ max_parts doc="Maximum number of parts to retrieve"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ part_number_marker doc="Specifies the part after which listing should begin. Only parts with higher part numbers will be listed."
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ limit doc="The maximum number of parts to retrieve over all iteration"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ xml_only doc="Set to TRUE to return XML data rather than a ListPartsIterator"
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ListParts extends AbstractRequestObject
{
    const MAX_PER_REQUEST = 1000;

    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request(RequestInterface::GET, $this->get('bucket'), $this->get('key'));
        
        $this->request->getQuery()->set('uploadId', $this->get('upload_id'));

        if ($this->get('part_number_marker')) {
            $this->request->getQuery()->set('part-number-marker', $this->get('part_number_marker'));
        }
        
        if ($this->get('max_parts')) {
            $this->request->getQuery()->set('max-parts', $this->get('max_parts'));
        }

        $this->applyDefaults($this->request);
    }

    /**
     * {@inheritdoc}
     */
    protected function process()
    {
        $xml = new \SimpleXMLElement($this->getResponse()->getBody(true));
        if ($this->get('xml_only')) {
             $this->result = $xml;
        } else {
            $this->result = ListPartsIterator::factory($this->/* Replaced /* Replaced /* Replaced client */ */ */, $xml, $this->get('limit', -1));
        }
    }

    /**
     * Returns a ListPartsIterator object
     *
     * @return ListPartsIterator
     */
    public function getResult()
    {
        return parent::getResult();
    }

    /**
     * Set the max number of parts to retrieve per request
     *
     * @param int $maxParts Maximum number of parts to retrieve per request
     *
     * @return ListParts
     */
    public function setMaxParts($maxParts)
    {
        return $this->set('max_parts', (int)$maxParts);
    }

    /**
     * Set the part after which listing should begin. Only parts with higher
     * part numbers will be listed.
     *
     * @param int $marker Next part marker
     *
     * @return ListParts
     */
    public function setPartNumberMarker($marker)
    {
        return $this->set('part_number_marker', $marker);
    }

    /**
     * Set the upload ID
     *
     * @param string $uploadId Upload ID
     *
     * @return ListParts
     */
    public function setUploadId($uploadId)
    {
        return $this->set('upload_id', $uploadId);
    }

    /**
     * Set to TRUE to format the response only as XML rather than create a new
     * ListPartsIterator
     *
     * @param bool $xmlResponseOnly
     *
     * @return ListParts
     */
    public function setXmlResponseOnly($xmlResponseOnly)
    {
        return $this->set('xml_only', $xmlResponseOnly);
    }

    /**
     * Set the maximum number of parts to retrieve when iterating over results.
     *
     * @param integer $limit Maximum numbuer of parts to retrieve with the iterator
     *
     * @return ListParts
     */
    public function setLimit($limit)
    {
        $this->set('limit', max(0, $limit));
        if ($limit < self::MAX_PER_REQUEST) {
            $this->setMaxParts($limit);
        }

        return $this;
    }
}