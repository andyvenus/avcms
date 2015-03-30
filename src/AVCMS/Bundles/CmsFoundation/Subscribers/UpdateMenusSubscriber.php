<?php
/**
 * User: Andy
 * Date: 28/08/2014
 * Time: 15:16
 */

namespace AVCMS\Bundles\CmsFoundation\Subscribers;

use AV\Kernel\Bundle\BundleManagerInterface;
use AVCMS\Bundles\CmsFoundation\Model\Menu;
use AVCMS\Bundles\CmsFoundation\Model\MenuItemConfig;
use AVCMS\Core\Menu\MenuManager;
use AVCMS\Core\View\TemplateManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UpdateMenusSubscriber implements EventSubscriberInterface
{
    private $bundleManager;

    private $templateManager;

    private $menuManager;

    public function __construct(MenuManager $menuManager, TemplateManager $templateManager, BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;
        $this->templateManager = $templateManager;
        $this->menuManager = $menuManager;
    }

    public function updateMenus(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$this->templateManager->cacheIsFresh()) {
            $config = $this->templateManager->getTemplateConfig();

            if (isset($config['menus'])) {
                $this->menuManager->setMenusInactiveByProvider('template');
                $this->processMenuConfig($config['menus'], 'template', $config['details']['name']);
            }
        }

        if (!$this->bundleManager->cacheIsFresh()) {
            $this->menuManager->setMenusInactiveByProvider('bundle');
            $this->menuManager->getItemsModel()->query()->whereNotNull('owner')->update(['provider_enabled' => 0]);

            foreach ($this->bundleManager->getBundleConfigs() as $bundle) {
                if (isset($bundle['menus'])) {
                    $this->processMenuConfig($bundle['menus'], 'bundle', $bundle['name']);
                }

                if (isset($bundle['menu_items']) && is_array($bundle['menu_items'])) {
                    foreach ($bundle['menu_items'] as $menu => $menuItems) {
                        foreach ($menuItems as $itemId => $menuItemConfig) {
                            $menuItem = $this->menuManager->getItemsModel()->query()->where('provider_id', $itemId)->first();

                            if (!$menuItem) {
                                $menuItem = new MenuItemConfig();
                            }

                            unset($parent);
                            if (isset($menuItemConfig['parent'])) {
                                $parent = $menuItemConfig['parent'];
                                unset($menuItemConfig['parent']);
                            }

                            $menuItem->fromArray($menuItemConfig, true);
                            $menuItem->setMenu($menu);
                            $menuItem->setProviderId($itemId);
                            $menuItem->setOwner($bundle->name);
                            $menuItem->setProviderEnabled(1);

                            if ($menuItem->getOrder() === null && isset($menuItemConfig['default_order'])) {
                               $menuItem->setOrder($menuItemConfig['default_order']);
                            }

                            if (isset($parent) && $menuItem->getId() === null) {
                                $parentItem = $this->menuManager->getItemsModel()->query()->where('provider_id', $parent)->first();
                                if ($parentItem) {
                                    $menuItem->setParent($parentItem->getId());
                                }
                            }

                            // In case someone has used true/false for the translatable parameter
                            if ($menuItemConfig['translatable']) {
                                $menuItem->setTranslatable(1);
                            }
                            else {
                                $menuItem->setTranslatable(0);
                            }

                            $this->menuManager->saveMenuItemConfig($menuItem);
                        }
                    }
                }
            }
        }
    }

    private function processMenuConfig($config, $provider, $owner)
    {
        foreach ($config as $menuId => $menuConfig) {
            $menu = new Menu();
            $menu->setId($menuId);
            $menu->setCustom(false);
            $menu->setActive(1);
            $menu->setProvider($provider);
            $menu->setOwner($owner);
            $menu->fromArray($menuConfig);

            $this->menuManager->saveMenu($menu);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('updateMenus')
        );
    }
}
