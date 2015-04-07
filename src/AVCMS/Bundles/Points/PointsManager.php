<?php
/**
 * User: Andy
 * Date: 20/02/15
 * Time: 12:24
 */

namespace AVCMS\Bundles\Points;

use AVCMS\Bundles\Users\Model\Users;
use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PointsManager
{
    protected $users;

    protected $settingsManager;

    protected $tokenStorage;

    protected $session;

    public function __construct(SessionInterface $session, TokenStorageInterface $tokenStorage, Users $users, SettingsManager $settingsManager)
    {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->users = $users;
        $this->settingsManager = $settingsManager;
    }

    public function addPoints($settingName, $notification)
    {
        $lastPoints = $this->session->get('last_points/'.$settingName, 0);

        if ($lastPoints > time() - 120) {
            $this->session->set('points_notification', ['message' => 'No points earned this time, you\'re earning too fast']);
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user->getId()) {
            return;
        }

        $points = $this->settingsManager->getSetting($settingName);

        if (!is_numeric($points)) {
            $points = 1;
        }

        if ($points == 0) {
            return;
        }

        $this->users->query()
            ->where('id', $user->getId())
            ->update(['points__points' => $this->users->query()->raw('points__points + '.$points)]);

        $this->session->set('last_points/'.$settingName, time());
        $this->session->set('points_notification', ['message' => $notification, 'points' => $points]);
    }
}
