<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 10:58
 */

namespace AVCMS\Bundles\FacebookConnect\Facebook;

use AVCMS\Bundles\FacebookConnect\FacebookRedirectLoginHelper;
use AVCMS\Core\SettingsManager\SettingsManager;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
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

    public function __construct(SettingsManager $settings, UrlGeneratorInterface $urlGenerator, SessionInterface $session)
    {
        $this->settings = $settings;
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;

        FacebookSession::setDefaultApplication($settings->getSetting('facebook_app_id'), $settings->getSetting('facebook_secret'));
    }

    public function getHelper()
    {
        if (!isset($this->helper)) {
            $url = $this->urlGenerator->generate('facebook_login_check', [], UrlGeneratorInterface::ABSOLUTE_URL);

            $this->helper = new FacebookRedirectLoginHelper($url);
            $this->helper->setSession($this->session);
        }

        return $this->helper;
    }

    public function createSession($accessToken)
    {
        return new FacebookSession($accessToken);
    }

    /**
     * @param FacebookSession $session
     * @param $method
     * @param $path
     * @param null $parameters
     * @param null $version
     * @param null $etag
     * @return FacebookRequest
     */
    public function createRequest(FacebookSession $session, $method, $path, $parameters = null, $version = null, $etag = null)
    {
        return new FacebookRequest($session, $method, $path, $parameters, $version, $etag);
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
