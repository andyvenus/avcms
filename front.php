<?php

use AV\Kernel\BundleKernel;
use AV\Kernel\Bundle\BundleManager;
use AVCMS\Core\Kernel\AvcmsKernel;
use Symfony\Component\HttpFoundation\Request;
use AVCMS\Core\Bundle\Config\BundleConfigValidator;

define('DEBUG_MODE', true);
define('ROOT_DIR', __DIR__);

require_once __DIR__.'/vendor/autoload.php';

$avcms = new AvcmsKernel(ROOT_DIR, DEBUG_MODE);

$request = Request::createFromGlobals();
$response = $avcms->handle($request);

$response->send();

$avcms->terminate($request, $response);