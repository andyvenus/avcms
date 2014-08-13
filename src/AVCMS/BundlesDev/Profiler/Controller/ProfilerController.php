<?php
/**
 * User: Andy
 * Date: 04/08/2014
 * Time: 12:13
 */

namespace AVCMS\BundlesDev\Profiler\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfilerController extends Controller
{
    public function profilerHomeAction($token)
    {
        $profiler = $this->container->get('profiler');
        $profile = $profiler->loadProfile($token);

        $memory = $profile->getCollector('memory');
        $time = $profile->getCollector('time');
        $translations = $profile->getCollector('translations');
        $request_profiler = $profile->getCollector('request');

        return new Response($this->render('@Profiler/profiler_main.twig', array(
            'memory' => $memory,
            'time' => $time,
            'translations' => $translations,
            'request' => $request_profiler
        )));
    }
} 