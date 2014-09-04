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
    protected $browser_template = '@Admin/menus_browser.twig';

    /**
     * @var \AVCMS\Bundles\CmsFoundation\Model\Menus
     */
    protected $menus;

    /**
     * @var \AVCMS\Bundles\CmsFoundation\Model\MenuItems
     */
    protected $menu_items;

    public function setUp()
    {
        $this->menus = $this->model('Menus');
        $this->menu_items  = $this->model('MenuItems');
    }

    public function homeAction(Request $request)
    {
       return $this->createManageResponse($request, $this->browser_template);
    }

    public function editMenuAction(Request $request)
    {
        $form = $this->buildForm(new MenuAdminForm($request->attributes->get('id', 0)));

        $helper = $this->editContentHelper($this->menus, $form);

        $helper->handleRequestAndSave($request);

        if (!$helper->contentExists()) {
            throw $this->createNotFoundException();
        }

        return $this->createEditResponse($helper, $request, '@Admin/edit_menu.twig', $this->browser_template, array('menus_admin_edit', array('id' => $helper->getEntity()->getId())));
    }

    public function editMenuItemAction(Request $request)
    {
        $menu = $this->menus->getOne($request->get('menu'));

        if (!$menu) {
            throw $this->createNotFoundException(sprintf("Menu %s not found", $request->get('menu')));
        }

        $form = $this->buildForm(new MenuItemAdminForm($request->attributes->get('id', 0)));

        $menu_items = $this->menu_items;

        $menu_item = $menu_items->getOneOrNew($request->attributes->get('id', 0));

        if (!$menu_item) {
            throw $this->createNotFoundException('Menu Item Not Found');
        }

        $menu_item->setMenu($menu->getId());

        $helper = $this->editContentHelper($this->model('MenuItems'), $form, $menu_item);

        $helper->handleRequestAndSave($request);

        if (!$helper->contentExists()) {
            throw $this->createNotFoundException();
        }

        return $this->createEditResponse(
            $helper,
            $request,
            '@CmsFoundation/edit_menu_item.twig',
            $this->browser_template,
            array('menus_admin_manage_items', array('id' => $menu->getId())),
            array('menu' => $menu))
        ;

    }

    public function manageMenuItemsAction(Request $request)
    {
        $menu_manager = $this->container->get('menu_manager');

        $menu = $menu_manager->getModel()->getOneOrNew($request->get('id', 0));

        if (!$menu) {
            throw $this->createNotFoundException('Menu Not Found');
        }

        $menu_items = $menu_manager->getMenuItems($menu->getId());

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

        $model = $this->model('Menus');

        $model->query()->where('custom', 1)->whereIn('id', (array) $request->request->get('ids'));

        $menu_items = $this->model('MenuItems');

        $menu_items->query()->where('owner', 'user')->whereIn('menu', (array) $request->request->get('ids'))->delete();

        return new JsonResponse(array('success' => 1));
    }

    public function saveOrderAction(Request $request, $id)
    {
        if (!$request->get('menu_order')) {
            throw $this->createNotFoundException("No menu ordering data found in request");
        }

        $menu_items_model = $this->model('MenuItems');
        $menu_items = $menu_items_model->query()->where('menu', $id)->get();
        $order = $request->get('menu_order');

        $i = 0;
        foreach ($order as $id => $parent) {
            $i++;

            if (isset($menu_items[$id])) {
                /**
                 * @var $menu_item \AVCMS\Bundles\CmsFoundation\Model\MenuItem
                 */
                $menu_item = $menu_items[$id];

                if ($parent == 'null') {
                    $parent = null;
                }

                $menu_item->setParent($parent);
                $menu_item->setOrder($i);

                $menu_items_model->update($menu_item);
            }
        }

        return new JsonResponse(array('success' => true));
    }
}