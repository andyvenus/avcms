<?php
/**
 * User: Andy
 * Date: 05/04/15
 * Time: 13:48
 */

namespace AVCMS\Bundles\CmsFoundation\Subscribers;

use Assetic\AssetWriter;
use AV\Kernel\Bundle\BundleManagerInterface;
use AVCMS\Core\AssetManager\AssetManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PostInstallSubscriber implements EventSubscriberInterface
{
    /**
     * @var BundleManagerInterface
     */
    protected $bundleManager;

    /**
     * @var AssetManager
     */
    protected $assetManager;

    public function __construct(BundleManagerInterface $bundleManager, AssetManager $assetManager)
    {
        $this->bundleManager = $bundleManager;
        $this->assetManager = $assetManager;
    }

    public function postInstallActions(GetResponseEvent $event)
    {
        if ($event->isMasterRequest() && $event->getRequest()->get('install_complete') && $this->bundleManager->cacheIsFresh() === false) {
            try {
                $this->assetManager->generateProductionAssets(new AssetWriter('web/compiled'));
            }
            catch (\Exception $e) {}
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['postInstallActions']
        ];
    }
}
