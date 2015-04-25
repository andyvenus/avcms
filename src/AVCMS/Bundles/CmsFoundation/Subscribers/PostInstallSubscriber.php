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
use AVCMS\Core\Bundle\PublicFileMover;
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

    /**
     * @var PublicFileMover
     */
    protected $fileMover;

    public function __construct(BundleManagerInterface $bundleManager, AssetManager $assetManager, PublicFileMover $publicFileMover)
    {
        $this->bundleManager = $bundleManager;
        $this->assetManager = $assetManager;
        $this->fileMover = $publicFileMover;
    }

    public function postInstallActions(GetResponseEvent $event)
    {
        if ($event->isMasterRequest() && $event->getRequest()->get('install_complete') && $this->bundleManager->cacheIsFresh() === false) {
            try {
                $this->fileMover->doMove(true);
                $this->assetManager->generateProductionAssets(new AssetWriter('web/compiled'));
            }
            catch (\Exception $e) { }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['postInstallActions', -50]
        ];
    }
}
