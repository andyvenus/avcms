<?php
/**
 * User: Andy
 * Date: 26/08/2014
 * Time: 12:07
 */

namespace AVCMS\Core\Menu;

use AVCMS\Bundles\CmsFoundation\Model\MenuItem;
use AVCMS\Core\Model\Model;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MenuManager
{
    /**
     * @var \AVCMS\Core\Model\Model
     */
    protected $model;
    /**
     * @var \AVCMS\Core\Model\Model
     */
    protected $itemsModel;

    public function __construct(UrlGeneratorInterface $url_generator, Model $menusModel, Model $itemsModel)
    {
        $this->urlGenerator = $url_generator;
        $this->model = $menusModel;
        $this->itemsModel = $itemsModel;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getItemsModel()
    {
        return $this->itemsModel;
    }

    public function saveMenuItem(MenuItem $menuItem)
    {
        $this->itemsModel->save($menuItem);
    }

    public function getMenuItems($menuId)
    {
        /**
         * @var $items object[]
         */
        $items =  $this->itemsModel->query()->where('menu', $menuId)->orderBy('order')->get();
        $childItems = $sortedItems = array();

        foreach ($items as $item) {
            $item->children = array();

            if ($item->getParent()) {
                $childItems[$item->getParent()][$item->getId()] = $item;
            }
            else {
                $sortedItems[$item->getId()] = $item;
            }

            if ($item->getType() == 'route') {
                try {
                    $item->setUrl($this->urlGenerator->generate($item->getTarget()));
                }
                catch (RouteNotFoundException $e) {
                    $item->setUrl('#route-'.$item->getTarget().'-not-found');
                }
            }
            else {
                $item->setUrl($item->getTarget());
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