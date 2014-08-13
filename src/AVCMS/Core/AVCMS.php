<?php

namespace AVCMS\Core;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

class AVCMS implements HttpKernelInterface, TerminableInterface
{
    /**
     * @var bool
     */
    private $booted = false;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private $container;

    /**
     * @var
     */
    private $debug;

    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    public function boot()
    {
        $this->buildContainer();
    }

    /**
     * @param Request $request
     * @param int $type
     * @param bool $catch
     * @return Response
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        if (false === $this->booted) {
            $this->boot();
        }

        $this->updateContext($request);

        return $this->getHttpKernel()->handle($request, $type, $catch);
    }

    public function terminate(Request $request, Response $response)
    {
        return $this->getHttpKernel()->terminate($request, $response);
    }

    private function buildContainer()
    {
        $container = new ContainerBuilder();
        $container->setParameter('routes', include __DIR__ . '/../../../app/routes.php');
        $container->setParameter('dev_mode', $this->debug);
        $container->setParameter('container', $container);

        $services = array(
            'AVCMS\Services\Foundation',
            'AVCMS\Services\Bundles',
            'AVCMS\Services\Twig',
            'AVCMS\Services\Database',
            'AVCMS\Services\Form',
            'AVCMS\Services\User',
            'AVCMS\Services\Translation',
            'AVCMS\Services\Assets',
            'AVCMS\Services\Taxonomy',
        );

        if ($this->debug) {
            $services = array_merge($services, array(
                'AVCMS\Services\Developer',
                'AVCMS\Services\Profiler')
            );
        }

        foreach ($services as $service) {
            $service_class = new $service();
            $service_class->getServices(array(), $container);
        }

        $this->container = $container;

        $this->getBundleManager()->initBundles();

        $this->container->compile();
    }

    private function getBundleManager()
    {
        return $this->container->get('bundle_manager');
    }

    private function getHttpKernel()
    {
        return $this->container->get('http_kernel');
    }

    private function updateContext(Request $request)
    {
        $this->container->get('context')
            ->fromRequest($request);
    }
}