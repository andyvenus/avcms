<?php
/**
 * User: Andy
 * Date: 11/01/15
 * Time: 10:59
 */

namespace AVCMS\Bundles\Wallpapers\EventSubscriber;

use AVCMS\Core\Module\ModuleManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WallpaperModuleCacheBusterSubscriber implements EventSubscriberInterface
{
    private $moduleManager;

    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    public function clearCaches($event)
    {
        if ($event->getModel()->getSingular() === 'wallpaper') {
            $this->moduleManager->clearCaches(['wallpapers', 'liked_wallpapers']);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'admin.toggle_published' => ['clearCaches'],
            'admin.delete' => ['clearCaches']
        ];
    }
}
