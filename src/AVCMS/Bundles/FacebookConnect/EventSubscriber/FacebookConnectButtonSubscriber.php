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

    public function __construct(FacebookConnect $facebookConnect, $webDir)
    {
        $this->facebookConnect = $facebookConnect;
        $this->webDir = $webDir;
    }

    public function addButton(OutletEvent $event)
    {
        $outlet = $event->getOutletName();

        if ($outlet !== 'login.buttons' && $outlet !== 'register.top') {
            return;
        }

        $url = $this->facebookConnect->getHelper()->getLoginUrl();

        $content = '<a href="'.$url.'" class="btn btn-primary"><img src="'.$this->webDir.'/resources/FacebookConnect/images/fb_icon.png" width="18" height="18" /> Log-in With Facebook</a>';

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
