<?php
/**
 * User: Andy
 * Date: 26/08/2014
 * Time: 12:07
 */

namespace AVCMS\Core\Menu;

use AVCMS\Bundles\CmsFoundation\Model\MenuItem;
use AV\Model\Model;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

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

    protected $securityContext;

    public function __construct(UrlGeneratorInterface $urlGenerator, Model $menusModel, Model $itemsModel, SecurityContextInterface $securityContext)
    {
        $this->urlGenerator = $urlGenerator;
        $this->model = $menusModel;
        $this->itemsModel = $itemsModel;
        $this->securityContext = $securityContext;
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

        foreach ($items as $item) {
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