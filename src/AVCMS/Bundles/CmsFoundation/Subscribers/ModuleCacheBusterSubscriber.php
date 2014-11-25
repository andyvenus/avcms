<?php
/**
 * User: Andy
 * Date: 16/11/14
 * Time: 14:24
 */

namespace AVCMS\Bundles\CmsFoundation\Subscribers;

use AV\Kernel\Bundle\BundleManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ModuleCacheBusterSubscriber implements EventSubscriberInterface
{
    protected $bundleManager;

    protected $cacheFile;

    protected $cacheDir;

    public function __construct(BundleManagerInterface $bundleManager, $cacheDir)
    {
        $this->bundleManager = $bundleManager;
        $this->cacheDir = $cacheDir;
        $this->cacheFile = $cacheDir . '/module_cache_bust.php';
    }

    public function bustSelfCache()
    {
        if (!$this->bundleManager->cacheIsFresh()) {
            unlink($this->cacheFile);
        }
    }

    public function bustModuleCache($event)
    {
        $cacheBustConfig = $this->getCacheBustConfig();

        $class = get_class($event->getModel());

        if (isset($cacheBustConfig[$class])) {
            foreach ($cacheBustConfig[$class] as $cacheBust) {
                if (file_exists($this->cacheDir.'/'.$cacheBust['module'])) {
                    $dirItr = new \DirectoryIterator($this->cacheDir.'/'.$cacheBust['module']);

                    foreach ($dirItr as $cacheFile) {
                        if ($cacheFile->isFile()) {
                            unlink($cacheFile->getRealPath());
                        }
                    }

                    rmdir($this->cacheDir.'/'.$cacheBust['module']);
                }
            }
        }
    }

    protected function getCacheBustConfig()
    {
        if (!file_exists($this->cacheFile)) {
            $cacheBust = [];
            foreach ($this->bundleManager->getBundleConfigs() as $bundleConfig) {
                if (isset($bundleConfig['modules'])) {
                    foreach ($bundleConfig['modules'] as $moduleId => $module) {
                        if (isset($module['cache_bust_model'])) {
                            $cacheBust[$bundleConfig['namespace'] .'\\Model\\'. $module['cache_bust_model']][] = ['module' => $moduleId];
                        }
                    }
                }
            }

            if (!file_exists(dirname($this->cacheFile))) {
                mkdir(dirname($this->cacheFile), 0777, true);
            }

            file_put_contents($this->cacheFile, '<?php return '.var_export($cacheBust, true).';');
        }

        return require $this->cacheFile;
    }

    public static function getSubscribedEvents()
    {
        return [
            'model.insert' => ['bustModuleCache'],
            'model.update' => ['bustModuleCache'],
            KernelEvents::REQUEST => ['bustSelfCache']
        ];
    }
}