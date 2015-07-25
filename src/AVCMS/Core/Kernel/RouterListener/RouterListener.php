<?php
/**
 * User: Andy
 * Date: 25/07/15
 * Time: 10:45
 */

namespace AVCMS\Core\Kernel\RouterListener;

use Symfony\Component\HttpKernel\KernelEvents;

class RouterListener extends \Symfony\Component\HttpKernel\EventListener\RouterListener
{
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', -101)),
            KernelEvents::FINISH_REQUEST => array(array('onKernelFinishRequest', 0)),
        );
    }
}
