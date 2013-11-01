==========================
cURL authentication plugin
==========================

.. warning::

    The CurlAuthPlugin is deprecated. You should use the `auth` parameter of a /* Replaced /* Replaced /* Replaced client */ */ */ to add authorization headers to
    every request created by a /* Replaced /* Replaced /* Replaced client */ */ */.

    .. code-block:: php

        $/* Replaced /* Replaced /* Replaced client */ */ */->setDefaultOption('auth', array('username', 'password', 'Basic|Digest|NTLM|Any'));

If your web service /* Replaced /* Replaced /* Replaced client */ */ */ requires basic authorization, then you can use the CurlAuthPlugin to easily add an
Authorization header to each request sent by the /* Replaced /* Replaced /* Replaced client */ */ */.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\CurlAuth\CurlAuthPlugin;

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');

    // Add the auth plugin to the /* Replaced /* Replaced /* Replaced client */ */ */ object
    $authPlugin = new CurlAuthPlugin('username', 'password');
    $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($authPlugin);

    $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('projects/1/people')->send();
    $xml = new SimpleXMLElement($response->getBody(true));
    foreach ($xml->person as $person) {
        echo $person->email . "\n";
    }
