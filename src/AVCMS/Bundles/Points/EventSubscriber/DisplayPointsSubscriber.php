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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DisplayPointsSubscriber implements EventSubscriberInterface
{
    protected $settingsManager;

    protected $tokenStorage;

    public function __construct(SettingsManager $settingsManager, TokenStorageInterface $tokenStorage)
    {
        $this->settingsManager = $settingsManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function displayPoints(OutletEvent $outletEvent)
    {
        if ($outletEvent->getOutletName() !== 'user_details') {
            return;
        }

        $user = $outletEvent->getVars()['user'];

        $currentUser = $this->tokenStorage->getToken()->getUser();

        $class = '';
        if ($user->getId() == $currentUser->getId()) {
            $class = 'avcms-active-user-points';
        }

        $outletEvent->addContent('<span class="label label-default '.$class.'" data-toggle="tooltip" data-placement="bottom" title="'.$this->settingsManager->getSetting('points_name').'">'.$user->points.'</span>');
    }

    public static function getSubscribedEvents()
    {
        return [
            'twig.outlet' => 'displayPoints'
        ];
    }
}
