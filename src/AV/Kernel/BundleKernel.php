<?php

namespace AV\Kernel;

use AV\Kernel\Bundle\BundleManager;
use AV\Kernel\Bundle\BundleManagerInterface;
use AV\Kernel\Events\KernelBootEvent;
use AV\Kernel\Exception\KernelConfigException;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Dumper\XmlDumper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\Yaml\Yaml;

class BundleKernel implements HttpKernelInterface, TerminableInterface
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var BundleManagerInterface
     */
    protected $bundleManager;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $appConfig;

    /**
     * @var array
     */
    protected $bundleDirs;

    public function __construct($rootDir, $debug = false, array $options = array())
    {
        $this->options = $this->mergeDefaultOptions($options);

        $configLocation = $this->options['app_dir'].'/config/app.yml';

        if (!file_exists($configLocation)) {
            throw new KernelConfigException('No app config (app.yml) found in '.$this->options['app_dir']);
        }

        $this->appConfig = Yaml::parse(file_get_contents($configLocation));

        if (!isset($this->appConfig['bundle_dirs'])) {
            throw new KernelConfigException('App config (app.yml) does not contain any bundle directories');
        }

        $this->bundleDirs = $this->appConfig['bundle_dirs'];
        $this->debug = $debug;
        $this->rootDir = $rootDir;

        $this->buildBundleManager();
    }

    public function boot()
    {
        $this->booted = true;

        $this->bundleManager->initBundles();

        $this->buildContainer();

        // Set BundleManager alias
        if ($this->container->has('bundle_manager')) {
            $this->container->get('bundle_manager')->setBundleManager($this->getBundleManager());
        }

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

            if (file_exists($cacheDir = $this->options['cache_dir'].'/container.php')) {
                unlink($cacheDir);
            }
        }
        else {
            $filenameAppend = '';
        }

        $containerCacheFile = $this->options['cache_dir'].'/container'.$filenameAppend.'.php';
        $containerConfigCache = new ConfigCache($containerCacheFile, $this->debug);

        if (!$containerConfigCache->isFresh()) {
            $container = new ContainerBuilder();
            $container->setParameter('dev_mode', $this->debug);
            $container->setParameter('root_dir', $this->rootDir);
            $container->setParameter('app_dir', $this->options['app_dir']);
            $container->setParameter('cache_dir', $this->options['cache_dir']);
            $container->setParameter('config_dir', $this->options['app_dir'].'/config');
            $container->setParameter('web_path', $this->options['web_path']);
            $container->setParameter('app_config', $this->appConfig);

            $container->addResource(new DirectoryResource($this->options['config_dir']));

            $this->container = $container;

            $this->getBundleManager()->decorateContainer($container);

            $this->container->compile();

            if ($this->options['container_cache'] !== false) {
                $dumper = new PhpDumper($this->container);
                $containerConfigCache->write(
                    $dumper->dump(),
                    $this->container->getResources()
                );
            }

            if ($this->debug) {
                $xmldumper = new XmlDumper($this->container);
                file_put_contents($this->options['cache_dir'].'/debugContainer.xml', $xmldumper->dump());
            }
        }
        else {
            require $containerCacheFile;
            $this->container = new \ProjectServiceContainer();
        }
    }

    /**
     * @return \AV\Kernel\Bundle\BundleManager
     */
    protected function getBundleManager()
    {
        if (!isset($this->bundleManager)) {
            $this->buildBundleManager($this->bundleDirs);
        }

        return $this->bundleManager;
    }

    protected function getHttpKernel()
    {
        return $this->container->get('http_kernel');
    }

    protected function updateContext(Request $request)
    {
        $this->container->get('context')
            ->fromRequest($request);
    }

    protected function buildBundleManager()
    {
        $this->bundleManager = new BundleManager($this->bundleDirs, $this->options['app_dir'].'/config', $this->options['cache_dir']);
        $this->bundleManager->setDebug($this->debug);
    }

    protected function mergeDefaultOptions(array $options)
    {
        $defaults = array(
            'app_dir' => 'app',
	        'cache_dir' => 'cache',
            'web_path' => 'web',
            'container_cache' => true
        );

        $options = array_merge($defaults, $options);

        $options['config_dir'] = $options['app_dir'].'/config';

        return $options;
    }
}
