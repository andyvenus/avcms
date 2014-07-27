<?php

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;

$request = Request::createFromGlobals();

$sc = include __DIR__ . '/app/container.php';
$sc->setParameter('routes', include __DIR__ . '/app/routes.php');
$sc->get('bundle_manager')->initBundles();

/**
 * @var $response Symfony\Component\HttpFoundation\Response
 */
$response = $sc->get('framework')->handle($request);

$response->send();

function bytesToSize($bytes, $precision = 2)
{
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;

    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' B';

    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' KB';

    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' MB';

    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' GB';

    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' TB';
    } else {
        return $bytes . ' B';
    }
}

// TODO: REMOVE THIS HOLY MOLY
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 3);

$filename = str_replace('/avcms/front.php/', '', $_SERVER["REQUEST_URI"]);
$filename = str_replace('/', '-', $filename);


$file = fopen('cache/loadtimes/'.$filename.'.txt', 'w');
fwrite($file, 'Time: '.$total_time.' - Memory: Peak: '.bytesToSize(memory_get_peak_usage()). ' Now: '.bytesToSize(memory_get_usage()));
fclose($file);

//echo '// Time: '.$total_time.' &nbsp;Memory: Peak: '.bytesToSize(memory_get_peak_usage()). ' Now: '.bytesToSize(memory_get_usage()) ;

$sc->get('framework')->terminate($request, $response);