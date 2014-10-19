<?php

use AV\Kernel\BundleKernel;
use AV\Kernel\Bundle\BundleManager;
use Symfony\Component\HttpFoundation\Request;

define('DEBUG_MODE', true);
define('ROOT_DIR', __DIR__);

require_once __DIR__.'/vendor/autoload.php';

$bundle_manager = new BundleManager(array(
    'src/AVCMS/Bundles',
    'src/AVCMS/BundlesDev',
));

$avcms = new BundleKernel($bundle_manager, DEBUG_MODE, ROOT_DIR);

$request = Request::createFromGlobals();
$response = $avcms->handle($request);

$response->send();

$avcms->terminate($request, $response);