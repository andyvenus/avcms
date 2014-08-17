<?php

use Symfony\Component\HttpFoundation\Request;
use AVCMS\Core\Kernel\BundleKernel;

require_once __DIR__.'/vendor/autoload.php';

$request = Request::createFromGlobals();

$avcms = new BundleKernel(true);

$response = $avcms->handle($request);
$response->send();

$avcms->terminate($request, $response);