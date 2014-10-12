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
    private $rootDir;

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

    public function __construct(BundleManager $bundleManager, $debug, $rootDir)
    {
        $bundleManager->setDebug($debug);

        $this->bundleManager = $bundleManager;
        $this->debug = $debug;
        $this->rootDir = $rootDir;
    }

    public function boot()
    {
        $this->bundleManager->initBundles();

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
        $this->getHttpKernel()->terminate($request, $response);
    }

    private function buildContainer()
    {
        if ($this->debug) {
            $filenameAppend = '_dev';

            if (file_exists('cache/container.php')) {
                unlink('cache/container.php');
            }
        }
        else {
            $filenameAppend = '';
        }

        $containerCacheFile = 'cache/container'.$filenameAppend.'.php';
        $container_config_cache = new ConfigCache($containerCacheFile, $this->debug);

        if (!$container_config_cache->isFresh()) {
            $container = new ContainerBuilder();
            $container->setParameter('dev_mode', $this->debug);
            $container->setParameter('root_dir', $this->rootDir);

            $loader = new YamlFileLoader($container, new FileLocator('app/config'));
            $loader->load('app.yml');

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
            require $containerCacheFile;
            $this->container = new \ProjectServiceContainer();
        }
    }

    /**
     * @return \AVCMS\Core\Bundle\BundleManager
     */
    private function getBundleManager()
    {
        return $this->bundleManager;
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