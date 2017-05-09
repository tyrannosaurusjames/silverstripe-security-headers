<?php

namespace Guttmann\SilverStripe;

use Config;
use Controller;
use Director;
use FunctionalTest;

class SecurityHeaderControllerExtensionTest extends FunctionalTest
{

    private $originalHeaderValues = null;

    private static $testHeaders = array(
        'Content-Security-Policy' => 'test-value',
        'Strict-Transport-Security' => 'test-value',
        'Frame-Options' => 'test-value',
        'X-XSS-Protection' => 'test-value',
        'X-Content-Type-Options' => 'test-value'
    );

    public function setUpOnce()
    {
        Controller::add_extension('Guttmann\SilverStripe\SecurityHeaderControllerExtension');
        Config::inst()->update('Director', 'rules', array(
            'security-header-test' => 'Controller'
        ));

        $this->originalHeaderValues = Config::inst()->get('Guttmann\SilverStripe\SecurityHeaderControllerExtension', 'headers');

        Config::inst()->update('Guttmann\SilverStripe\SecurityHeaderControllerExtension', 'headers', self::$testHeaders);
    }

    public function tearDownOnce()
    {
        Controller::remove_extension('Guttmann\SilverStripe\SecurityHeaderControllerExtension');
        Config::inst()->remove('Director', 'rules', 'security-header-test');
        Config::inst()->update('Guttmann\SilverStripe\SecurityHeaderControllerExtension', 'headers', $this->originalHeaderValues);
    }

    public function testResponseHeaders()
    {
        $response = Director::test('security-header-test');

        $headersReceived = $response->getHeaders();

        foreach ($headersReceived as $header => $value) {
            if (in_array($header, array_keys(self::$testHeaders))) {
                $this->assertEquals($value, self::$testHeaders[$header]);
            }
        }
    }

    public function testBrowserHasWorkingCSPImplementation()
    {
        $safari5Response = $this->getResponseForUserAgent(
            'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; de-de) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27'
        );

        $this->assertNull($safari5Response->getHeader('Content-Security-Policy'));

        $safari7Response = $this->getResponseForUserAgent(
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A'
        );

        $this->assertEquals('test-value', $safari7Response->getHeader('Content-Security-Policy'));

        $chromeResponse = $this->getResponseForUserAgent(
            'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36'
        );

        $this->assertEquals('test-value', $chromeResponse->getHeader('Content-Security-Policy'));
    }

    private function getResponseForUserAgent($userAgent)
    {
        return Director::test(
            'security-header-test',
            null,
            null,
            null,
            null,
            array(
                'User-Agent' => $userAgent
            )
        );
    }

}
