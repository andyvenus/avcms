<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 10:58
 */

namespace AVCMS\Bundles\FacebookConnect\Facebook;

use AVCMS\Core\SettingsManager\SettingsManager;
use Facebook\Facebook;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FacebookConnect
{
    /**
     * @var SettingsManager
     */
    protected $settings;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var
     */
    protected $helper;

    /**
     * @var Facebook
     */
    protected $facebook;

    public function __construct(SettingsManager $settings, UrlGeneratorInterface $urlGenerator, SessionInterface $session)
    {
        $this->settings = $settings;
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;

        $enabled = $settings->getSetting('facebook_connect');
        $appId = $settings->getSetting('facebook_app_id');
        $secret = $settings->getSetting('facebook_secret');

        if ($enabled && $appId && $secret) {
            $this->facebook = new Facebook([
                'app_id' => $settings->getSetting('facebook_app_id'),
                'app_secret' => $settings->getSetting('facebook_secret'),
                'default_graph_version' => 'v2.2',
                'persistent_data_handler' => new SymfonySession($session)
            ]);
        }
    }

    public function getHelper()
    {
        return $this->facebook->getRedirectLoginHelper();
    }

    public function api()
    {
        return $this->facebook;
    }

    public function setDefaultAccessToken($token)
    {
        $this->facebook->setDefaultAccessToken($token);
    }

    public function isEnabled()
    {
        return (
            $this->settings->getSetting('facebook_connect') === '1'
            && $this->settings->getSetting('facebook_app_id')
            && $this->settings->getSetting('facebook_secret')
        );
    }
}
