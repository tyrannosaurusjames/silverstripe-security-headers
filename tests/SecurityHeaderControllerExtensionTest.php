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
        'X-XXS-Protection' => 'test-value',
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

}
