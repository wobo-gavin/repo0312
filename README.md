/* Replaced /* Replaced /* Replaced Guzzle */ */ */, PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ and webservice framework
================================================

[![Build Status](https://secure.travis-ci.org//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */.png?branch=master)](http://travis-ci.org//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */)

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ is a PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ that makes it easy to work with HTTP/1.1 and takes
the pain out of consuming web services.

```php
$/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client();
$response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org');
$res = $/* Replaced /* Replaced /* Replaced client */ */ */->get('https://api.github.com/user', ['auth' =>  ['user', 'pass']]);
echo $res->getStatusCode();
// 200
echo $res->getHeader('content-type');
// 'application/json; charset=utf8'
echo $res->getBody();
// {"type":"User"...'
var_export($res->json());
// Outputs the JSON decoded data
```

- Pluggable HTTP adapters that can send requests serially or in parallel
- Doesn't require cURL, but uses cURL by default
- Streams data for both uploads and downloads
- Provides event hooks & plugins for cookies, caching, logging, OAuth, mocks,
  etc...
- Keep-Alive & connection pooling
- SSL Verification
- Automatic decompression of response bodies
- Streaming multipart file uploads
- Connection timeouts

Get more information and answers with the
[Documentation](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/),
[Forums](https://groups.google.com/forum/?hl=en#!forum//* Replaced /* Replaced /* Replaced guzzle */ */ */),
and IRC ([#/* Replaced /* Replaced /* Replaced guzzle */ */ */php](irc://irc.freenode.net/#/* Replaced /* Replaced /* Replaced guzzle */ */ */php) @ irc.freenode.net).

### Installing via Composer

The recommended way to install /* Replaced /* Replaced /* Replaced Guzzle */ */ */ is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, update your project's composer.json file to include /* Replaced /* Replaced /* Replaced Guzzle */ */ */:

```javascript
{
    "require": {
        "/* Replaced /* Replaced /* Replaced guzzle */ */ */http//* Replaced /* Replaced /* Replaced guzzle */ */ */": "4.0.0-rc.1"
    }
}
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

### Documentation

More information can be found in the online documentation at
http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/.
