<?php
/**
 * User: Andy
 * Date: 14/01/15
 * Time: 11:05
 */

namespace AVCMS\Bundles\Wallpapers\EventSubscriber;

use AVCMS\Bundles\CmsFoundation\Event\OutletEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;

class WallpaperCacheOutletSubscriber implements EventSubscriberInterface
{
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function addMenuItem(OutletEvent $outletEvent)
    {
        if ($outletEvent->getOutletName() !== 'admin.options_menu_caches') {
            return;
        }

        $outletEvent->addContent('<li><a class="clear-wallpaper-cache" href="#">'.$this->translator->trans('Clear Wallpaper Image Cache').'</a></li>');
    }

    public static function getSubscribedEvents()
    {
        return [
            'twig.outlet' => 'addMenuItem'
        ];
    }
}
