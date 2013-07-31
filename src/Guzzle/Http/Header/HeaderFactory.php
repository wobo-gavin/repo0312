<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Header;

/**
 * Default header factory implementation
 */
class HeaderFactory implements HeaderFactoryInterface
{
    /** @var array */
    protected $mapping = array(
        'cache-control' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Header\CacheControl',
        'link'          => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Header\Link',
    );

    public function createHeader($header, $value = null)
    {
        $lowercase = strtolower($header);

        return isset($this->mapping[$lowercase])
            ? new $this->mapping[$lowercase]($header, $value)
            : new DefaultHeader($header, $value);
    }
}
