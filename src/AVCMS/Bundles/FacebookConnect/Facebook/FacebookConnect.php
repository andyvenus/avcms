<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 10:58
 */

namespace AVCMS\Bundles\FacebookConnect\Facebook;

use AVCMS\Bundles\FacebookConnect\FacebookRedirectLoginHelper;
use AVCMS\Core\SettingsManager\SettingsManager;
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
    }

    public function getHelper()
    {
        if (!isset($this->helper)) {
            $url = $this->urlGenerator->generate('facebook_login_check', [], UrlGeneratorInterface::ABSOLUTE_URL);

            $this->helper = new FacebookRedirectLoginHelper($url, '1535722406709073', '83145ae5b94ce97884a1e50be68f6991');
            $this->helper->setSession($this->session);
        }

        return $this->helper;
    }
}
