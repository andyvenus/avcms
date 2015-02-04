<?php

use AV\Model\Exception\DatabaseConfigMissingException;
use AVCMS\Core\Kernel\AvcmsKernel;
use Symfony\Component\HttpFoundation\Request;

define('DEBUG_MODE', true);
define('ROOT_DIR', __DIR__);

require_once __DIR__.'/vendor/autoload.php';

$avcms = new AvcmsKernel(ROOT_DIR, DEBUG_MODE, ['app_dir' => 'wallpapersitescript']);

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
