<?php

require_once __DIR__.'/vendor/autoload.php';

use AVCMS\Bundles\CmsFoundation\Services\DatabaseServices;
use AV\Bundles\Framework\Services\FrameworkServices;
use AVCMS\Installer\Services\InstallerServices;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

$container = new ContainerBuilder();
$container->setParameter('dev_mode', false);

$frameworkServices = new FrameworkServices();
$frameworkServices->getServices(array(), $container);
$databaseServices = new DatabaseServices();
$databaseServices->getServices(array(), $container);
$installerServices = new InstallerServices;
$installerServices->getServices(array(), $container);

$container->compile();

$kernel = $container->get('http_kernel');

$request = Request::createFromGlobals();
$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);