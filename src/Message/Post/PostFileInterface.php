<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Post;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\HasHeadersInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\StreamInterface;

/**
 * Post file upload interface
 */
interface PostFileInterface extends HasHeadersInterface
{
    /**
     * Get the name of the form field
     *
     * @return string
     */
    public function getName();

    /**
     * Get the full path to the file
     *
     * @return string
     */
    public function getFilename();

    /**
     * Get the content
     *
     * @return StreamInterface
     */
    public function getContent();
}
