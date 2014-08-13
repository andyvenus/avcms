<?php
/**
 * User: Andy
 * Date: 30/07/2014
 * Time: 15:56
 */

namespace AVCMS\BundlesDev\Profiler\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DevBarController extends Controller
{
    public function devBarAction(Request $request)
    {
        if (!$request->get('token')) {
            return new Response('No token');
        }

        $profiler = $this->container->get('profiler');
        $profile = $profiler->loadProfile($request->get('token'));

        $memory = $profile->getCollector('memory');
        $time = $profile->getCollector('time');
        $translations = $profile->getCollector('translations');
        $request_profiler = $profile->getCollector('request');

        return new Response($this->render('@Profiler/dev_bar.twig', array(
            'memory' => $memory,
            'time' => $time,
            'translations' => $translations,
            'request' => $request_profiler,
            'token' => $request->get('token')
        )));
    }
} 