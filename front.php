<?php

use AVCMS\Core\Kernel\BundleKernel;
use AVCMS\Core\Bundle\BundleManager;
use Symfony\Component\HttpFoundation\Request;

define('DEBUG_MODE', true);

require_once __DIR__.'/vendor/autoload.php';

$bundle_manager = new BundleManager(array(
    'src/AVCMS/Bundles',
    'src/AVCMS/BundlesDev',
));

$avcms = new BundleKernel($bundle_manager, DEBUG_MODE);

$request = Request::createFromGlobals();
$response = $avcms->handle($request);

$response->send();

$avcms->terminate($request, $response);