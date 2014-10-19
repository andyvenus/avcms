<?php
/**
 * User: Andy
 * Date: 28/08/2014
 * Time: 15:16
 */

namespace AVCMS\Bundles\CmsFoundation\Subscribers;

use AVCMS\Bundles\CmsFoundation\Model\MenuItem;
use AV\Kernel\Bundle\BundleManagerInterface;
use AVCMS\Core\Menu\MenuManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class UpdateMenusSubscriber implements EventSubscriberInterface
{
    protected $bundleManager;

    public function __construct(MenuManager $menuManager, BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;
        $this->menuManager = $menuManager;
    }

    public function updateMenus()
    {
        // Only update menu items if bundle configs have been modified
        if (!$this->bundleManager->cacheIsFresh()) {
            foreach ($this->bundleManager->getBundleConfigs() as $bundle) {
                if (isset($bundle['menu_items']) && is_array($bundle['menu_items'])) {
                    foreach ($bundle['menu_items'] as $menu => $menuItems) {
                        foreach ($menuItems as $itemId => $menuItemConfig) {
                            $menuItem = $this->menuManager->getItemsModel()->getOne($itemId);

                            if (!$menuItem) {
                                $menuItem = new MenuItem();
                            }

                            $menuItem->fromArray($menuItemConfig);
                            $menuItem->setMenu($menu);
                            $menuItem->setId($itemId);
                            $menuItem->setOwner($bundle->name);

                            $this->menuManager->saveMenuItem($menuItem);
                        }
                    }
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('updateMenus')
        );
    }
}