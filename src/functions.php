<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\UriTemplate;

/**
 * Send a custom request
 *
 * @param string $method  HTTP request method
 * @param string $url     URL of the request
 * @param array  $options Options to use with the request.
 *
 * @return ResponseInterface
 */
function request($method, $url, array $options = [])
{
    static $/* Replaced /* Replaced /* Replaced client */ */ */;
    if (!$/* Replaced /* Replaced /* Replaced client */ */ */) {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
    }

    return $/* Replaced /* Replaced /* Replaced client */ */ */->send($/* Replaced /* Replaced /* Replaced client */ */ */->createRequest($method, $url, $options));
}

/**
 * Send a GET request
 *
 * @param string $url     URL of the request
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function get($url, array $options = [])
{
    return request('GET', $url, $options);
}

/**
 * Send a HEAD request
 *
 * @param string $url     URL of the request
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function head($url, array $options = [])
{
    return request('HEAD', $url, $options);
}

/**
 * Send a DELETE request
 *
 * @param string $url     URL of the request
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function delete($url, array $options = [])
{
    return request('DELETE', $url, $options);
}

/**
 * Send a POST request
 *
 * @param string $url     URL of the request
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function post($url, array $options = [])
{
    return request('POST', $url, $options);
}

/**
 * Send a PUT request
 *
 * @param string $url     URL of the request
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function put($url, array $options = [])
{
    return request('PUT', $url, $options);
}

/**
 * Send a PATCH request
 *
 * @param string $url     URL of the request
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function patch($url, array $options = [])
{
    return request('PATCH', $url, $options);
}

/**
 * Send an OPTIONS request
 *
 * @param string $url     URL of the request
 * @param array  $options Array of request options
 *
 * @return ResponseInterface
 */
function options($url, array $options = [])
{
    return request('OPTIONS', $url, $options);
}

/**
 * Gets a value from an array using a path syntax to retrieve nested data.
 *
 * This method does not allow for keys that contain "/". You must traverse
 * the array manually or using something more advanced like JMESPath to
 * work with keys that contain "/".
 *
 *     // Get the bar key of a set of nested arrays.
 *     // This is equivalent to $collection['foo']['baz']['bar'] but won't
 *     // throw warnings for missing keys.
 *     /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\get_path($data, 'foo/baz/bar');
 *
 * @param array  $data Data to retrieve values from
 * @param string $path Path to traverse and retrieve a value from
 *
 * @return mixed|null
 */
function get_path($data, $path)
{
    $path = explode('/', $path);

    while (null !== ($part = array_shift($path))) {
        if (!is_array($data) || !isset($data[$part])) {
            return null;
        }
        $data = $data[$part];
    }

    return $data;
}

/**
 * Set a value in a nested array key. Keys will be created as needed to set the
 * value.
 *
 * This function does not support keys that contain "/" or "[]" characters
 * because these are special tokens used when traversing the data structure.
 * A value may be prepended to an existing array by using "[]" as the final
 * key of a path.
 *
 *     /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\get_path($data, 'foo/baz'); // null
 *     /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\set_path($data, 'foo/baz/[]', 'a');
 *     /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\set_path($data, 'foo/baz/[]', 'b');
 *     /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\get_path($data, 'foo/baz');
 *     // Returns ['a', 'b']
 *
 * @param array  $data  Data to modify by reference
 * @param string $path  Path to set
 * @param mixed  $value Value to set at the key
 * @throws \RuntimeException when trying to setPath using a nested path that
 *     travels through a scalar value.
 */
function set_path(&$data, $path, $value)
{
    $current =& $data;
    $queue = explode('/', $path);
    while (null !== ($key = array_shift($queue))) {
        if (!is_array($current)) {
            throw new \RuntimeException("Trying to setPath {$path}, but "
                . "{$key} is set and is not an array");
        } elseif (!$queue) {
            if ($key == '[]') {
                $current[] = $value;
            } else {
                $current[$key] = $value;
            }
        } elseif (isset($current[$key])) {
            $current =& $current[$key];
        } else {
            $current[$key] = [];
            $current =& $current[$key];
        }
    }
}

/**
 * Expands a URI template
 *
 * @param string $template  URI template
 * @param array  $variables Template variables
 *
 * @return string
 */
function uri_template($template, array $variables)
{
    if (function_exists('\\uri_template')) {
        return \uri_template($template, $variables);
    }

    static $uriTemplate;
    if (!$uriTemplate) {
        $uriTemplate = new UriTemplate();
    }

    return $uriTemplate->expand($template, $variables);
}

/**
 * @internal
 */
function deprecation_proxy($object, $name, $arguments, $map)
{
    if (!isset($map[$name])) {
        throw new \BadMethodCallException('Unknown method, ' . $name);
    }

    $message = sprintf('%s is deprecated and will be removed in a future '
        . 'version. Update your code to use the equivalent %s method '
        . 'instead to avoid breaking changes when this shim is removed.',
        get_class($object) . '::' . $name . '()',
        get_class($object) . '::' . $map[$name] . '()'
    );

    trigger_error($message, E_USER_DEPRECATED);

    return call_user_func_array([$object, $map[$name]], $arguments);
}
