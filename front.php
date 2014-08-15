<?php

use Symfony\Component\HttpFoundation\Request;
use AVCMS\Core\Kernel\AVCMS;

require_once __DIR__.'/vendor/autoload.php';

$request = Request::createFromGlobals();

$avcms = new AVCMS(true);

$response = $avcms->handle($request);
$response->send();

$avcms->terminate($request, $response);