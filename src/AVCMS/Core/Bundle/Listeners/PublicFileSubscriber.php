<?php
/**
 * User: Andy
 * Date: 25/08/2014
 * Time: 10:55
 */

namespace AVCMS\Core\Bundle\Listeners;

use AV\Kernel\Bundle\BundleManagerInterface;
use DirectoryIterator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class PublicFileSubscriber implements EventSubscriberInterface
{
    protected $bundleManager;

    protected $cacheDir;

    public function __construct(BundleManagerInterface $bundleManager, $cacheDir = 'cache')
    {
        $this->bundleManager = $bundleManager;
        $this->cacheDir = $cacheDir;
    }

    public function moveFiles(GetResponseEvent $event)
    {
        $cacheFile = $this->cacheDir.'/public_asset_last_move.txt';

        $lastTime = 0;

        if (file_exists($cacheFile) && $this->bundleManager->cacheIsFresh()) {
            $lastTime = file_get_contents($cacheFile);
        }

        if ($event->getRequestType() === HttpKernelInterface::MASTER_REQUEST && $this->bundleManager->isDebug()) {
            $bundles = $this->bundleManager->getBundleConfigs();

            foreach ($bundles as $bundle) {
                if (file_exists($bundle->directory.'/resources')) {
                    $this->copyDirectory($bundle->directory.'/resources', 'web/resources/'.$bundle->name, $lastTime, array('templates', 'translations'));
                }
            }
        }

        file_put_contents($cacheFile, time());
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('moveFiles')
        );
    }

    private function copyDirectory($src, $dst, $lastTime, array $ignore = null) {
        $dir = opendir($src);

        if (!file_exists($dst)) {
            mkdir($dst);
        }

        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' ) && ($ignore == null || !in_array($file, $ignore))) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->copyDirectory($src . '/' . $file, $dst . '/' . $file, $lastTime, $ignore);
                }
                elseif (filemtime($src . '/' . $file) > $lastTime) {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}