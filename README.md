/* Replaced /* Replaced /* Replaced Guzzle */ */ */, PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */
=======================

[![Build Status](https://secure.travis-ci.org//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */.png?branch=master)](http://travis-ci.org//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */)

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ is a PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ that makes it easy to send HTTP requests and
trivial to integrate with web services.

- Simple interface for building query strings, POST requests, streaming large
  uploads, streaming large downloads, using HTTP cookies, uploading JSON data,
  etc...
- Can send both synchronous and asynchronous requests using the same interface.
- Uses PSR-7 interfaces for requests, responses, and streams. This allows you
  to utilize other PSR-7 compatible libraries with /* Replaced /* Replaced /* Replaced Guzzle */ */ */.
- Abstracts away the underlying HTTP transport, allowing you to write
  environment and transport agnostic code; i.e., no hard dependency on cURL,
  PHP streams, sockets, or non-blocking event loops.
- Middleware system allows you to augment and compose /* Replaced /* Replaced /* Replaced client */ */ */ behavior.

```php
$/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client();
$res = $/* Replaced /* Replaced /* Replaced client */ */ */->get('https://api.github.com/user', ['auth' =>  ['user', 'pass']]);
echo $res->getStatusCode();
// "200"
echo $res->getHeader('content-type');
// 'application/json; charset=utf8'
echo $res->getBody();
// {"type":"User"...'

// Send an asynchronous request.
$request = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request('GET', 'http://httpbin.org');
$promise = $/* Replaced /* Replaced /* Replaced client */ */ */->sendAsync($req)->then(function ($response) {
    echo 'I completed! ' . $response;
});
$promise->wait();
```

## Help and docs

- [Documentation](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/)
- [stackoverflow](http://stackoverflow.com/questions/tagged//* Replaced /* Replaced /* Replaced guzzle */ */ */)
- [Gitter](https://gitter.im//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */)


## Installing /* Replaced /* Replaced /* Replaced Guzzle */ */ */

The recommended way to install /* Replaced /* Replaced /* Replaced Guzzle */ */ */ is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of /* Replaced /* Replaced /* Replaced Guzzle */ */ */:

```bash
composer.phar require /* Replaced /* Replaced /* Replaced guzzle */ */ */http//* Replaced /* Replaced /* Replaced guzzle */ */ */
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

You can then later update /* Replaced /* Replaced /* Replaced Guzzle */ */ */ using composer:

 ```bash
composer.phar update
 ```
