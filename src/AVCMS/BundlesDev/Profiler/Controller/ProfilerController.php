<?php
/**
 * User: Andy
 * Date: 04/08/2014
 * Time: 12:13
 */

namespace AVCMS\BundlesDev\Profiler\Controller;

use AVCMS\BundlesDev\Profiler\Form\ProfilerSearchForm;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfilerController extends Controller
{
    public function profilerHomeAction($token)
    {
        $profiler = $this->container->get('profiler');

        if (($profile = $profiler->loadProfile($token)) === null) {
            throw $this->createNotFoundException();
        }

        $memory = $profile->getCollector('memory');
        $time = $profile->getCollector('time');
        $translations = $profile->getCollector('translations');
        $request_profiler = $profile->getCollector('request');

        return new Response($this->render('@Profiler/profiler_main.twig', array(
            'memory' => $memory,
            'time' => $time,
            'translations' => $translations,
            'request' => $request_profiler,
        )));
    }

    public function profilerSearchAction(Request $request)
    {

        $form = $this->buildForm(new ProfilerSearchForm(), $request);

        $profiler = $this->container->get('profiler');

        $profiler->disable();

        $ip     = $request->query->get('ip', null);
        $method = $request->query->get('method');
        $url    = $request->query->get('url');
        $start  = $request->query->get('start', null);
        $end    = $request->query->get('end', null);
        $limit  = $request->query->get('limit', 20);

        return new Response($this->render('@Profiler/search_results.twig', array(
            'tokens'    => $profiler->find($ip, $url, $limit, $method, $start, $end),
            'ip'        => $ip,
            'method'    => $method,
            'url'       => $url,
            'start'     => $start,
            'end'       => $end,
            'limit'     => $limit,
            'panel'     => null,
            'form'      => $form->createView()
        )), 200, array('Content-Type' => 'text/html'));
    }
} 