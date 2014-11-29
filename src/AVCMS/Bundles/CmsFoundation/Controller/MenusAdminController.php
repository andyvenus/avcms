<?php

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Admin\Form\MenusAdminFiltersForm;
use AVCMS\Bundles\CmsFoundation\Form\MenuAdminForm;

use AVCMS\Bundles\CmsFoundation\Form\MenuItemAdminForm;
use AVCMS\Core\Content\EditContentHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MenusAdminController extends AdminBaseController
{
    protected $browserTemplate = '@Admin/menus_browser.twig';

    /**
     * @var \AVCMS\Bundles\CmsFoundation\Model\Menus
     */
    protected $menus;

    /**
     * @var \AVCMS\Bundles\CmsFoundation\Model\MenuItems
     */
    protected $menuItems;

    public function setUp()
    {
        $this->menus = $this->model('Menus');
        $this->menuItems  = $this->model('MenuItems');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, $this->browserTemplate);
    }

    public function editMenuAction(Request $request)
    {
        $form = $this->buildForm(new MenuAdminForm($request->attributes->get('id', 0)));

        $helper = $this->editContentHelper($this->menus, $form);

        $helper->handleRequestAndSave($request);

        if (!$helper->contentExists()) {
            throw $this->createNotFoundException();
        }

        return $this->createEditResponse($helper, $request, '@Admin/edit_menu.twig', $this->browserTemplate, array('menus_admin_edit', array('id' => $helper->getEntity()->getId())));
    }

    public function editMenuItemAction(Request $request)
    {
        $menu = $this->menus->getOne($request->get('menu'));

        if (!$menu) {
            throw $this->createNotFoundException(sprintf("Menu %s not found", $request->get('menu')));
        }

        $form = $this->buildForm(new MenuItemAdminForm($request->attributes->get('id', 0)));

        $menuItem = $this->menuItems->getOneOrNew($request->attributes->get('id', 0));

        if (!$menuItem) {
            throw $this->createNotFoundException('Menu Item Not Found');
        }

        $menuItem->setMenu($menu->getId());

        $helper = $this->editContentHelper($this->model('MenuItems'), $form, $menuItem);

        $helper->handleRequestAndSave($request);

        if (!$helper->contentExists()) {
            throw $this->createNotFoundException();
        }

        return $this->createEditResponse(
            $helper,
            $request,
            '@CmsFoundation/edit_menu_item.twig',
            $this->browserTemplate,
            array('menus_admin_manage_items', array('id' => $menu->getId())),
            array('menu' => $menu))
        ;

    }

    public function manageMenuItemsAction(Request $request)
    {
        $menuManager = $this->container->get('menu_manager');

        $menu = $menuManager->getModel()->getOneOrNew($request->get('id', 0));

        if (!$menu) {
            throw $this->createNotFoundException('Menu Not Found');
        }

        $menu_items = $menuManager->getMenuItems($menu->getId(), true);

        return new Response($this->renderAdminSection('@Admin/manage_menu_items.twig', $request->get('ajax_depth'), array(
            'item' => $menu,
            'menu_items' => $menu_items,
        )));
    }

    public function finderAction(Request $request)
    {
        $finder = $this->menus->find()
            ->setSearchFields(array('label'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Admin/menus_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteMenuAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        if (!$request->request->has('ids')) {
            return new JsonResponse(array('success' => 0, 'error' => 'No ids set'));
        }

        $this->menus->query()->whereIn('id', (array) $request->request->get('ids'))->delete();

        $this->menuItems->query()->where('owner', 'user')->whereIn('menu', (array) $request->request->get('ids'))->delete();

        return new JsonResponse(array('success' => 1));
    }

    public function deleteMenuItemAction(Request $request)
    {
        return $this->handleDelete($request, $this->menuItems);
    }

    public function saveOrderAction(Request $request, $id)
    {
        if (!$request->get('menu_order')) {
            throw $this->createNotFoundException("No menu ordering data found in request");
        }

        $menuItems = $this->menuItems->query()->where('menu', $id)->get();
        $order = $request->get('menu_order');

        $i = 0;
        foreach ($order as $id => $parent) {
            $i++;

            $id = str_replace('UNDERSCORE', '_', $id);
            $parent = str_replace('UNDERSCORE', '_', $parent);

            if (isset($menuItems[$id])) {
                /**
                 * @var $menu_item \AVCMS\Bundles\CmsFoundation\Model\MenuItem
                 */
                $menu_item = $menuItems[$id];

                if ($parent == 'null') {
                    $parent = null;
                }

                $menu_item->setParent($parent);
                $menu_item->setOrder($i);

                $this->menuItems->update($menu_item);
            }
        }

        return new JsonResponse(array('success' => true));
    }

    public function toggleMenuItemEnabledAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->menuItems, 'enabled');
    }
}