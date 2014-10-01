<?php
/*
 * This file is here for backwards compatibility with /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 4. Use the
 * functions available on /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Utils instead.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

if (!defined('GUZZLE_FUNCTIONS_VERSION')) {

    define('GUZZLE_FUNCTIONS_VERSION', ClientInterface::VERSION);

    /**
     * @param ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */
     * @param array           $requests
     * @param array           $options
     * @return \SplObjectStorage For backwards compatibility with v4
     * @deprecated Use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Pool::batch
     */
    function batch(ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */, $requests, array $options = [])
    {
        $result = Pool::batch($/* Replaced /* Replaced /* Replaced client */ */ */, $requests, $options);
        $hash = new \SplObjectStorage();
        foreach ($result->getKeys() as $request) {
            $hash[$request] = $result->getResult($request);
        }

        return $hash;
    }

    /**
     * @deprecated Use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Utils::getPath
     */
    function get_path($data, $path)
    {
        return Utils::getPath($data, $path);
    }

    /**
     * @deprecated Use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Utils::setPath
     */
    function set_path(&$data, $path, $value)
    {
        Utils::setPath($data, $path, $value);
    }

    /**
     * @deprecated Use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Utils::uriTemplate
     */
    function uri_template($template, array $variables)
    {
        return Utils::uriTemplate($template, $variables);
    }

    /**
     * @deprecated Use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Utils::jsonDecode
     */
    function json_decode($json, $assoc = false, $depth = 512, $options = 0)
    {
        return Utils::jsonDecode($json, $assoc, $depth, $options);
    }
}
