<?php
/**
 * User: Andy
 * Date: 20/02/15
 * Time: 16:16
 */

namespace AVCMS\Bundles\Points\EventSubscriber;

use AVCMS\Bundles\CmsFoundation\Event\OutletEvent;
use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DisplayPointsSubscriber implements EventSubscriberInterface
{
    protected $settingsManager;

    public function __construct(SettingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    public function displayPoints(OutletEvent $outletEvent)
    {
        if ($outletEvent->getOutletName() !== 'user_details') {
            return;
        }

        $user = $outletEvent->getVars()['user'];

        $outletEvent->addContent('<span class="label label-default" data-toggle="tooltip" data-placement="bottom" title="'.$this->settingsManager->getSetting('points_name').'">'.$user->points.'</span>');
    }

    public static function getSubscribedEvents()
    {
        return [
            'twig.outlet' => 'displayPoints'
        ];
    }
}
