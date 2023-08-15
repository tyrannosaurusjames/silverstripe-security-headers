<?php

namespace Guttmann\SilverStripe;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;

class SecurityHeaderControllerExtension extends Extension
{

    public function onAfterInit()
    {
        $response = $this->owner->getResponse();

        $headersToSend = (array) Config::inst()->get('Guttmann\SilverStripe\SecurityHeaderControllerExtension', 'headers');
        $xHeaderMap = (array) Config::inst()->get('Guttmann\SilverStripe\SecurityHeaderControllerExtension', 'x_headers_map');

        foreach ($headersToSend as $header => $value) {
            if ($header === 'Content-Security-Policy') {
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


}
