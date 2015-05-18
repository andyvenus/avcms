<?php
if (version_compare(PHP_VERSION, '5.4.0') === -1) {
    exit('PHP 5.4 or above is required, you are running version '.PHP_VERSION.' Older versions of PHP are no longer supported by the PHP developers and could become insecure.');
}

use AV\Model\Exception\DatabaseConfigMissingException;
use AVCMS\Core\Kernel\AvcmsKernel;
use Symfony\Component\HttpFoundation\Request;

ini_set("memory_limit", "250M");

define('DEBUG_MODE', true);
define('ROOT_DIR', __DIR__);

require_once __DIR__.'/vendor/autoload.php';

$avcms = new AvcmsKernel(ROOT_DIR, DEBUG_MODE, ['app_dir' => 'avcms_dev']);

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
