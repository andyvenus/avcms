<?php
/**
 * User: Andy
 * Date: 25/08/2014
 * Time: 10:55
 */

namespace AVCMS\Core\Bundle\Listeners;

use AVCMS\Core\Bundle\BundleManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class PublicFileSubscriber implements EventSubscriberInterface
{
    protected $bundleManager;

    public function __construct(BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;
    }

    public function moveFiles(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($event->getRequestType() === HttpKernelInterface::MASTER_REQUEST && $this->bundleManager->isDebug()) {
            $bundles = $this->bundleManager->getBundleConfigs();

            foreach ($bundles as $bundle) {
                if (file_exists($bundle->directory.'/resources')) {
                    $this->copyDirectory($bundle->directory.'/resources', 'web/resources/'.$bundle->name, array('templates', 'translations'));
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('moveFiles')
        );
    }

    private function copyDirectory($src, $dst, array $ignore = null) {
        $dir = opendir($src);

        if (!file_exists($dst)) {
            mkdir($dst);
        }

        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' ) && ($ignore == null || !in_array($file, $ignore))) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->copyDirectory($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}