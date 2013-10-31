=============
Cookie plugin
=============

Some web services require a Cookie in order to maintain a session. The ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie\CookiePlugin`` will add
cookies to requests and parse cookies from responses using a CookieJar object:

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie\CookiePlugin;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie\CookieJar\ArrayCookieJar;

    $cookiePlugin = new CookiePlugin(new ArrayCookieJar());

    // Add the cookie plugin to a /* Replaced /* Replaced /* Replaced client */ */ */
    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');
    $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($cookiePlugin);

    // Send the request with no cookies and parse the returned cookies
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.yahoo.com/')->send();

    // Send the request again, noticing that cookies are being sent
    $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.yahoo.com/');
    $request->send();

    echo $request;

You can disable cookies per-request by setting the ``cookies.disable`` value to true on a request's params object.

.. code-block:: php

    $request->getParams()->set('cookies.disable', true);
