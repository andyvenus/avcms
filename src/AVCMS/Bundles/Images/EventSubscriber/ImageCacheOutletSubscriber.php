<?php
/**
 * User: Andy
 * Date: 14/01/15
 * Time: 11:05
 */

namespace AVCMS\Bundles\Images\EventSubscriber;

use AVCMS\Bundles\CmsFoundation\Event\OutletEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ImageCacheOutletSubscriber implements EventSubscriberInterface
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

        $outletEvent->addContent('<li><a class="clear-image-thumbnails-cache" href="#"><span class="glyphicon glyphicon-picture"></span> '.$this->translator->trans('Clear Image Thumbnail Cache').'</a></li>');
    }

    public static function getSubscribedEvents()
    {
        return [
            'twig.outlet' => 'addMenuItem'
        ];
    }
}
