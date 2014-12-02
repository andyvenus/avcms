<?php

use AV\Kernel\BundleKernel;
use AV\Kernel\Bundle\BundleManager;
use AV\Model\Exception\DatabaseConfigMissingException;
use AVCMS\Core\Kernel\AvcmsKernel;
use Symfony\Component\HttpFoundation\Request;
use AVCMS\Core\Bundle\Config\BundleConfigValidator;

define('DEBUG_MODE', true);
define('ROOT_DIR', __DIR__);

require_once __DIR__.'/vendor/autoload.php';

$avcms = new AvcmsKernel(ROOT_DIR, DEBUG_MODE);

$request = Request::createFromGlobals();

try {
    $response = $avcms->handle($request);
}
catch (DatabaseConfigMissingException $e) {
    header('Location: install.php');
    exit();
}

$response->send();

$avcms->terminate($request, $response);