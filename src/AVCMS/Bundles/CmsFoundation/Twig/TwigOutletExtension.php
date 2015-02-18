<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 10:44
 */

namespace AVCMS\Bundles\CmsFoundation\Twig;

use AVCMS\Bundles\CmsFoundation\Event\OutletEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TwigOutletExtension extends \Twig_Extension
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function outlet($outletName, array $vars = [])
    {
        $event = new OutletEvent($outletName, $vars);

        $this->eventDispatcher->dispatch('twig.outlet', $event);

        return $event->getContent();
    }

    public function getFunctions()
    {
        return array(
            'outlet' => new \Twig_SimpleFunction('outlet',
                array($this, 'outlet'),
                array('is_safe' => array('html')
                )
            )
        );
    }

    public function getName()
    {
        return 'avcms_outlets';
    }
}
