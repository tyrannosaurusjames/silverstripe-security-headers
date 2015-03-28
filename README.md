# SilverStripe security headers

[![Build Status](https://travis-ci.org/guttmann/metapod.svg?branch=master)](https://travis-ci.org/guttmann/metapod)
[![Code Coverage](https://scrutinizer-ci.com/g/guttmann/metapod/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/guttmann/metapod/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/guttmann/metapod/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/guttmann/metapod/?branch=master)

SilverStripe module for easily adding a selection of [useful HTTP headers](https://www.owasp.org/index.php/List_of_useful_HTTP_headers).

Comes with a default set of headers configured, but can be used to add any headers you wish.

## Install

Install via [composer](https://getcomposer.org):

    composer require guttmann/silverstripe-security-headers 1.0.*

## Usage

### Apply the extension

Apply the `SecurityHeaderControllerExtension` to the controller of your choice.

For example, add this to your `mysite/_config/config.yml` file:

    Page_Controller:
      extensions:
        - SecurityHeaderControllerExtension

### Configure the headers

Configure header values to suit your site, it's important your config is loaded
after the security-headers module's config.

For example, your `mysite/_config/config.yml` file might look like this:

    ---
    Name: mysite
    After:
      - 'framework/*'
      - 'cms/*'
      - 'security-headers/*'
    ---
    SecurityHeaderControllerExtension:
      headers:
        Content-Security-Policy: "default-src 'self' *.google-analytics.com;"
        Strict-Transport-Security: "max-age=2592000"

## Disclaimer

I am not a security expert - the default header values used in this module are
based on advice I have received from a number of sources.

They are not set in stone and if you see any issues please send me a pull request.
