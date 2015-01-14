<?php
/**
 * User: Andy
 * Date: 14/01/15
 * Time: 19:30
 */

namespace AVCMS\Bundles\Wallpapers\EventSubscriber;

use AV\Kernel\Bundle\BundleManagerInterface;
use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class WallpaperCacheLimiterSubscriber implements EventSubscriberInterface
{
    protected $bundleManager;

    protected $rootDir;

    protected $webPath;

    protected $settingsManager;

    public function __construct($rootDir, $webPath, BundleManagerInterface $bundleManager, SettingsManager $settingsManager)
    {
        $this->rootDir = $rootDir;
        $this->webPath = $webPath;
        $this->bundleManager = $bundleManager;
        $this->settingsManager = $settingsManager;
    }

    public function cacheLimit(PostResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        $imageLimit = $this->settingsManager->getSetting('wallpaper_image_cache_limit');

        if (!is_numeric($imageLimit) || $imageLimit == '0') {
            return;
        }

        if ($request->get('_route') === 'wallpaper_image' && strpos($response->headers->get('Content-Type'), 'image') !== false && $request->get('thumbnail') !== true) {
            $config = $this->bundleManager->getBundleConfig('Wallpapers');

            $wallpaperCacheDir = $this->rootDir.'/'.$this->webPath.'/'.$config->config->web_dir.'/'.$request->get('slug');
            $newImageName = $request->get('width').'x'.$request->get('height');

            $totalFiles = 0;
            $fileList = [];
            foreach (new \DirectoryIterator($wallpaperCacheDir) as $file) {
                if ($file->isFile()) {
                    $totalFiles++;

                    if (strpos($file->getFilename(), $newImageName) === false) {
                        $fileList[] = $wallpaperCacheDir . '/' . $file->getFilename();
                    }
                }
            }

            if ($totalFiles > $imageLimit) {
                foreach ($fileList as $file) {
                    unlink($file);
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE => ['cacheLimit']
        ];
    }
}
