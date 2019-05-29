<?php

use Microsimulation\Journal\AppKernel;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

umask(0002);

require_once __DIR__.'/../vendor/autoload.php';

$_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? 'dev';
$_SERVER['APP_DEBUG'] = 'dev' === $_SERVER['APP_ENV'];

if ($_SERVER['APP_DEBUG']) {
    Debug::enable();
}

$kernel = new AppKernel($_SERVER['APP_ENV'], $_SERVER['APP_DEBUG']);

Request::enableHttpMethodParameterOverride();
Request::setTrustedProxies([$_SERVER['REMOTE_ADDR']], Request::HEADER_X_FORWARDED_ALL);

$kernel->run(Request::createFromGlobals());
