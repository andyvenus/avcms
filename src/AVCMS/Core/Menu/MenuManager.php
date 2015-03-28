<?php
/**
 * User: Andy
 * Date: 26/08/2014
 * Time: 12:07
 */

namespace AVCMS\Core\Menu;

use AV\Model\Model;
use AVCMS\Bundles\CmsFoundation\Model\Menu;
use AVCMS\Bundles\CmsFoundation\Model\MenuItemConfig;
use AVCMS\Core\Menu\Event\FilterMenuItemEvent;
use AVCMS\Core\Menu\MenuItemType\MenuItemTypeInterface;
use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Translation\TranslatorInterface;

class MenuManager
{
    /**
     * @var \AV\Model\Model
     */
    protected $model;
    /**
     * @var \AV\Model\Model
     */
    protected $menuItemConfigs;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var MenuItemTypeInterface[]
     */
    protected $menuItemTypes = [];

    /**
     * @var null|EventDispatcherInterface
     */
    protected $eventDispatcher = null;

    /**
     * @var SettingsManager
     */
    protected $settingsManager;

    public function __construct(UrlGeneratorInterface $urlGenerator, Model $menusModel, Model $itemsModel, SecurityContextInterface $securityContext, TranslatorInterface $translator, EventDispatcherInterface $eventDispatcher, SettingsManager $settingsManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->model = $menusModel;
        $this->menuItemConfigs = $itemsModel;
        $this->securityContext = $securityContext;
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;
        $this->settingsManager = $settingsManager;
    }

    public function addMenuItemType(MenuItemTypeInterface $menuItemType, $id)
    {
        $this->menuItemTypes[$id] = $menuItemType;
    }

    /**
     * @return MenuItemTypeInterface[]
     */
    public function getMenuItemTypes()
    {
        return $this->menuItemTypes;
    }

    /**
     * @param $id
     * @return MenuItemTypeInterface
     */
    public function getMenuItemType($id)
    {
        return isset($this->menuItemTypes[$id]) ? $this->menuItemTypes[$id] : null;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getItemsModel()
    {
        return $this->menuItemConfigs;
    }

    public function saveMenuItemConfig(MenuItemConfig $menuItem)
    {
        $this->menuItemConfigs->save($menuItem);
    }

    public function saveMenu(Menu $menu)
    {
        $this->model->save($menu);
    }

    public function setMenusInactiveByProvider($provider)
    {
        $this->model->query()->where('provider', $provider)->update(['active' => 0]);
    }

    public function getMenuItems($menuId, $showDisabled = false)
    {
        $query = $this->menuItemConfigs->query()->where('menu', $menuId)->orderBy('order');

        if ($showDisabled === false) {
            $query->where('enabled', '1');
        }

        /**
         * @var $configs MenuItemConfigInterface[]
         */
        $configs =  $query->get();
        $childItems = $sortedItems = array();

        foreach ($configs as $config) {

            if ($config->getTranslatable()) {
                $config->setLabel($this->translator->trans($config->getLabel()));
            }

            if (!isset($this->menuItemTypes[$config->getType()])) {
                continue;
            }

            $items = $this->menuItemTypes[$config->getType()]->getMenuItems($config);

            if (!is_array($items)) {
                $items = [$items];
            }

            /**
             * @var $item MenuItem
             */
            foreach ($items as $item) {
                if ($item->getPermission()) {
                    $permissions = explode(',', str_replace(' ', '', $item->getPermission()));
                    if (!$this->securityContext->isGranted($permissions)) {
                        continue;
                    }
                }

                if ($item->getAdminSetting() && !$this->settingsManager->getSetting($item->getAdminSetting())) {
                    continue;
                }

                $item->children = array();

                if ($this->eventDispatcher !== null) {
                    $this->eventDispatcher->dispatch('menu_manager.filter_item', new FilterMenuItemEvent($item, $config));
                }

                if ($item->getParent()) {
                    $childItems[$item->getParent()][$item->getId()] = $item;
                } else {
                    $sortedItems[$item->getId()] = $item;
                }
            }
        }

        foreach ($childItems as $parent => $child_item_array) {
            if (isset($sortedItems[$parent])) {
                $sortedItems[$parent]->children = $child_item_array;
            }
        }

        return $sortedItems;
    }

    public function getMenuItemConfigs($menuId)
    {
        $configs = $this->menuItemConfigs->query()->where('menu', $menuId)->orderBy('order')->get();

        $childItems = $sortedItems = array();

        foreach ($configs as $config) {
            if ($config->getParent()) {
                $childItems[$config->getParent()][$config->getId()] = $config;
            } else {
                $sortedItems[$config->getId()] = $config;
            }
        }

        foreach ($childItems as $parent => $child_item_array) {
            if (isset($sortedItems[$parent])) {
                $sortedItems[$parent]->children = $child_item_array;
            }
        }

        return $sortedItems;
    }
}
