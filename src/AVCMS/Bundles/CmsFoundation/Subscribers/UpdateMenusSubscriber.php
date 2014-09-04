<?php
/**
 * User: Andy
 * Date: 28/08/2014
 * Time: 15:16
 */

namespace AVCMS\Bundles\CmsFoundation\Subscribers;

use AVCMS\Core\Bundle\BundleManagerInterface;
use AVCMS\Core\Menu\MenuManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class UpdateMenusSubscriber implements EventSubscriberInterface
{
    protected $bundle_manager;

    public function __construct(MenuManager $menu_manager, BundleManagerInterface $bundle_manager)
    {
        $this->bundle_manager = $bundle_manager;
        $this->menu_manager = $menu_manager;
    }

    public function updateMenus()
    {
        // Only update menu items if bundle configs have been modified
        if (!$this->bundle_manager->cacheIsFresh()) {
            foreach ($this->bundle_manager->getBundleConfigs() as $bundle) {
                if (isset($bundle['menu_items']) && is_array($bundle['menu_items'])) {
                    foreach ($bundle['menu_items'] as $menu => $menu_items) {
                        foreach ($menu_items as $item_id => $menu_item_config) {
                            $menu_item = $this->menu_manager->getItemsModel()->getOneOrNew($item_id);

                            $menu_item->fromArray($menu_item_config);
                            $menu_item->setMenu($menu);
                            $menu_item->setId($item_id);
                            $menu_item->setOwner($bundle->name);

                            $this->menu_manager->saveMenuItem($menu_item);
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