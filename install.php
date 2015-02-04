<?php

if (file_exists('webmaster/installer_lock.txt')) exit('The installer is locked. To unlock, delete the webmaster/installer_lock.txt file');

use AVCMS\Core\Kernel\AvcmsKernel;
use Symfony\Component\HttpFoundation\Request;

define('DEBUG_MODE', true);
define('ROOT_DIR', __DIR__);

require_once __DIR__.'/vendor/autoload.php';

$avcms = new AvcmsKernel(ROOT_DIR, DEBUG_MODE, ['app_dir' => 'wallpapersitescript/install', 'cache_dir' => 'cache/installer']);

$request = Request::createFromGlobals();
$response = $avcms->handle($request);

$response->send();

$avcms->terminate($request, $response);
