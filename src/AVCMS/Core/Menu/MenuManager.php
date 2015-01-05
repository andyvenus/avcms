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
use AVCMS\Core\Menu\MenuItemType\MenuItemTypeInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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
    protected $itemsModel;

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
     * @var array
     */
    protected $menuItemTypes = [];

    public function __construct(UrlGeneratorInterface $urlGenerator, Model $menusModel, Model $itemsModel, SecurityContextInterface $securityContext, TranslatorInterface $translator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->model = $menusModel;
        $this->itemsModel = $itemsModel;
        $this->securityContext = $securityContext;
        $this->translator = $translator;
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
        return $this->itemsModel;
    }

    public function saveMenuItem(MenuItemConfig $menuItem)
    {
        $this->itemsModel->save($menuItem);
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
        $query = $this->itemsModel->query()->where('menu', $menuId)->orderBy('order');

        if ($showDisabled === false) {
            $query->where('enabled', '1');
        }

        /**
         * @var $items object[]
         */
        $items =  $query->get();
        $childItems = $sortedItems = array();

        foreach ($items as $config) {

            if ($config->getTranslatable()) {
                $config->setLabel($this->translator->trans($config->getLabel()));
            }

            if (!isset($this->menuItemTypes[$config->getType()])) {
                continue;
            }

            $item = $this->menuItemTypes[$config->getType()]->getMenuItems($config);

            if ($item->getPermission()) {
                $permissions = explode(',', str_replace(' ', '', $item->getPermission()));
                if (!$this->securityContext->isGranted($permissions)) {
                    continue;
                }
            }

            $item->children = array();

            if ($item->getParent()) {
                $childItems[$item->getParent()][$item->getId()] = $item;
            }
            else {
                $sortedItems[$item->getId()] = $item;
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
