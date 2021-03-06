<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 10:25
 */

namespace AVCMS\Bundles\FacebookConnect\EventSubscriber;

use AVCMS\Bundles\CmsFoundation\Event\OutletEvent;
use AVCMS\Bundles\FacebookConnect\Facebook\FacebookConnect;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FacebookConnectButtonSubscriber implements EventSubscriberInterface
{
    /**
     * @var FacebookConnect
     */
    private $facebookConnect;

    /**
     * @var string
     */
    private $webDir;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var
     */
    private $siteUrl;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(FacebookConnect $facebookConnect, $siteUrl, $webDir, TranslatorInterface $translator, UrlGeneratorInterface $urlGenerator)
    {
        $this->facebookConnect = $facebookConnect;
        $this->siteUrl = $siteUrl;
        $this->webDir = $webDir;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    public function addButton(OutletEvent $event)
    {
        if (!$this->facebookConnect->isEnabled()) {
            return;
        }

        $outlet = $event->getOutletName();

        if ($outlet !== 'login.buttons' && $outlet !== 'register.top') {
            return;
        }

        $url = $this->facebookConnect->getHelper()->getLoginUrl($this->urlGenerator->generate('facebook_login_check', [], UrlGeneratorInterface::ABSOLUTE_URL), ['email']);

        $content = '<a href="'.$url.'" class="btn btn-primary"><img src="'.$this->siteUrl.$this->webDir.'/resources/FacebookConnect/images/fb_icon.png" width="18" height="18" /> '.$this->translator->trans('Login With Facebook').'</a>';

        if ($outlet === 'register.top') {
            $content .= ' - or -';
        }

        $event->addContent($content);
    }

    public static function getSubscribedEvents()
    {
        return [
            'twig.outlet' => ['addButton']
        ];
    }
}
