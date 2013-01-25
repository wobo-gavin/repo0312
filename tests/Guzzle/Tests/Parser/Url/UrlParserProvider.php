<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Parser\Url;

class UrlParserProvider extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @return array
     */
    public function urlProvider()
    {
        $resp = array();
        foreach (array(
			'',
            'http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/',
            'http://www.google.com:8080/path?q=1&v=2',
            'https://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/?value1=a&value2=b',
            'https:///* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/index.html',
            '/index.html?q=2',
            'http://www.google.com:8080/path?q=1&v=2',
            'http://michael:123@www.google.com:8080/path?q=1&v=2',
            'http://michael@test.com/abc/def?q=10#test'
        ) as $url) {
            $parts = parse_url($url);
            $resp[] = array($url, parse_url($url));
        }

        return $resp;
    }
}
