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
    protected $items_model;

    public function __construct(UrlGeneratorInterface $url_generator, Model $menus_model, Model $items_model)
    {
        $this->url_generator = $url_generator;
        $this->model = $menus_model;
        $this->items_model = $items_model;
    }

    /**
     * @param MenuLoaderInterface $loader
     */
    public function load(MenuLoaderInterface $loader)
    {
        $loader->loadMenuItems($this);
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getItemsModel()
    {
        return $this->items_model;
    }

    public function saveMenuItem(MenuItem $menu_item)
    {
        $this->items_model->save($menu_item);
    }

    public function getMenuItems($menu_id)
    {
        /**
         * @var $items object[]
         */
        $items =  $this->items_model->query()->where('menu', $menu_id)->orderBy('order')->get();
        $child_items = $sorted_items = array();

        foreach ($items as $item) {
            $item->children = array();

            if ($item->getParent()) {
                $child_items[$item->getParent()][$item->getId()] = $item;
            }
            else {
                $sorted_items[$item->getId()] = $item;
            }

            if ($item->getType() == 'route') {
                try {
                    $item->setUrl($this->url_generator->generate($item->getTarget()));
                }
                catch (RouteNotFoundException $e) {
                    $item->setUrl('#route-'.$item->getTarget().'-not-found');
                }
            }
            else {
                $item->setUrl($item->getTarget());
            }
        }

        foreach ($child_items as $parent => $child_item_array) {
            if (isset($sorted_items[$parent])) {
                $sorted_items[$parent]->children = $child_item_array;
            }
        }

        return $sorted_items;
    }
}