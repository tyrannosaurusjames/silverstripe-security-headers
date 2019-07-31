<?php

namespace Guttmann\SilverStripe;

use Config;
use Extension;

class SecurityHeaderControllerExtension extends Extension
{
    public function onAfterInit()
    {
        $response = $this->owner->getResponse();

        $headersToSend = (array) Config::inst()->get('Guttmann\SilverStripe\SecurityHeaderControllerExtension', 'headers');
        $xHeaderMap = (array) Config::inst()->get('Guttmann\SilverStripe\SecurityHeaderControllerExtension', 'x_headers_map');

        foreach ($headersToSend as $header => $value) {
            if (empty($value)) {
                continue;
            }

            if ($header === 'Content-Security-Policy' && !$this->browserHasWorkingCSPImplementation()) {
                continue;
            }

            $response->addHeader($header, $value);

            if (isset($xHeaderMap[$header])) {
                foreach ($xHeaderMap[$header] as $xHeader) {
                    $response->addHeader($xHeader, $value);
                }
            }
        }
    }

    private function browserHasWorkingCSPImplementation()
    {
        $agent = strtolower(
            $this->owner->getRequest()->getHeader('User-Agent')
        );

        if (strpos($agent, 'safari') === false) {
            return true;
        }

        $split = explode('version/', $agent);

        if (!isset($split[1])) {
            return true;
        }

        $version = trim($split[1]);
        $versions = explode('.', $version);

        if (isset($versions[0]) && $versions[0] <= 5) {
            return false;
        }

        return true;
    }
}
