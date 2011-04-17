<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\Observer;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;

/**
 * HTTP request that sends an entity-body in the request message (POST, PUT)
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
interface EntityEnclosingRequestInterface extends RequestInterface, Observer
{
    /**
     * Set the body of the request
     *
     * @param string|resource|EntityBody $body Body to use in the entity body
     *      of the request
     *
     * @return EntityEnclosingRequestInterface
     */
    function setBody($body);

    /**
     * Get the body of the request if set
     *
     * @return EntityBody|null
     */
    function getBody();

    /**
     * Get the post fields that will be used in the request
     *
     * @return QueryString
     */
    function getPostFields();

    /**
     * Returns an array of files that will be sent in the request.
     *
     * The '@' prefix is removed from the files in the return array
     *
     * @return array
     */
    function getPostFiles();

    /**
     * Add the POST fields to use in the request
     *
     * @param QueryString|array $fields POST fields
     *
     * @return EntityEnclosingRequestInterface
     */
    function addPostFields($fields);

    /**
     * Add POST files to use in the upload
     *
     * @param array $files An array of filenames to POST
     *
     * @return EntityEnclosingRequestInterface
     *
     * @throws BodyException if the file cannot be read
     */
    function addPostFiles(array $files);
}