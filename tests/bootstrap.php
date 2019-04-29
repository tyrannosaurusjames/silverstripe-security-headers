<?php

$autoloadFilename = __DIR__ . '/../../vendor/autoload.php';
$frameworkBootstrapFilename = __DIR__ . '/../../vendor/silverstripe/framework/tests/bootstrap.php';

if (!file_exists($autoloadFilename)) {
    echo 'You must first install the vendors using composer.' . PHP_EOL;
    exit(1);
}

if (!file_exists($frameworkBootstrapFilename)) {
    echo 'Can\'t find framework bootstrap.php' . PHP_EOL;
    exit(1);
}

require $autoloadFilename;
require $frameworkBootstrapFilename;
