<?php

namespace AVCMS\Core\Kernel;

use AVCMS\Core\Bundle\BundleManager;
use AVCMS\Core\Kernel\Events\KernelBootEvent;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Dumper\XmlDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

class BundleKernel implements HttpKernelInterface, TerminableInterface
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

    public function __construct(BundleManager $bundle_manager, $debug)
    {
        $bundle_manager->setDebug($debug);

        $this->bundle_manager = $bundle_manager;
        $this->debug = $debug;
    }

    public function boot()
    {
        $this->bundle_manager->initBundles();

        $this->buildContainer();

        // Set BundleManager alias
        $this->container->get('bundle_manager')->setBundleManager($this->getBundleManager());

        $event_dispatcher = $this->container->get('dispatcher');
        $event_dispatcher->dispatch('kernel.boot', new KernelBootEvent($this->container));
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
        if ($this->debug) {
            $filename_append = '_dev';

            if (file_exists('cache/container.php')) {
                unlink('cache/container.php');
            }
        }
        else {
            $filename_append = '';
        }

        $container_cache_file = 'cache/container'.$filename_append.'.php';
        $container_config_cache = new ConfigCache($container_cache_file, $this->debug);

        if (!$container_config_cache->isFresh()) {
            $container = new ContainerBuilder();
            $container->setParameter('dev_mode', $this->debug);

            $loader = new YamlFileLoader($container, new FileLocator('app/config'));
            $loader->load('app.yml');

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
                ));
            }

            foreach ($services as $service) {
                $service_class = new $service();
                $service_class->getServices(array(), $container);

                $container->addObjectResource($service);
            }

            $this->container = $container;

            $this->getBundleManager()->decorateContainer($container);

            $this->container->compile();

            $dumper = new PhpDumper($this->container);
            $container_config_cache->write(
                $dumper->dump(),
                $this->container->getResources()
            );

            if ($this->debug) {
                $xmldumper = new XmlDumper($this->container);
                file_put_contents('cache/debugContainer.xml', $xmldumper->dump());
            }
        }
        else {
            require $container_cache_file;
            $this->container = new \ProjectServiceContainer();
        }
    }

    /**
     * @return \AVCMS\Core\Bundle\BundleManager
     */
    private function getBundleManager()
    {
        return $this->bundle_manager;
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