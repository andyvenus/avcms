<?php
/**
 * User: Andy
 * Date: 20/12/14
 * Time: 13:10
 */

namespace AVCMS\Bundles\Categories\Controller;

use AVCMS\Bundles\Categories\Form\CategoryAdminForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Categories\Model\Categories;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryControllerTrait
 * @package AVCMS\Bundles\Categories\Controller
 *
 * For use only in classes that extend AdminBaseController
 */
trait CategoryActionsTrait
{
    public function manageCategoriesAction(Request $request, $contentType)
    {
        $categoryConfig = $this->getCategoryConfig($contentType);

        if (!$categoryConfig) {
            throw $this->createNotFoundException(sprintf('Category config not found for content type "%s" in bundle', $contentType));
        }

        $model = $this->model($categoryConfig['model']);

        if (!$model instanceof Categories) {
            throw new \Exception("Model does not extend the base Categories model");
        }

        $categories = $model->getAllCategories(true);

        return new Response($this->renderAdminSection('@Categories/admin/manage_categories.twig', $request->get('ajax_depth'), ['categories' => $categories, 'browser_template' => $categoryConfig['browser_template'], 'route_prefix' => $categoryConfig['route_prefix']]));
    }

    public function saveOrderAction(Request $request, $contentType)
    {
        if (!$request->get('category_order')) {
            throw $this->createNotFoundException("No category ordering data found in request");
        }

        $categoryConfig = $this->getCategoryConfig($contentType);
        $model = $this->model($categoryConfig['model']);

        $categories = $model->query()->get();
        $order = $request->get('category_order');

        $i = 0;
        foreach ($order as $id => $parent) {
            $i++;

            if (isset($categories[$id])) {
                /**
                 * @var $category \AVCMS\Bundles\Categories\Model\Category
                 */
                $category = $categories[$id];

                if ($parent == 'null') {
                    $parent = null;
                }

                $category->setParent($parent);
                $category->setOrder($i);

                $model->update($category);
            }
        }

        return new JsonResponse(array('success' => true));
    }

    public function editCategoryAction(Request $request, $contentType)
    {
        $categoryConfig = $this->getCategoryConfig($contentType);
        $model = $this->model($categoryConfig['model']);

        $formBlueprint = new CategoryAdminForm(new CategoryChoicesProvider($this->model('WallpaperCategories'), false));

        return $this->handleEdit($request, $model, $formBlueprint, $categoryConfig['route_prefix'].'manage_categories', '@Categories/admin/edit_category.twig', $categoryConfig['browser_template'], ['route_prefix' => $categoryConfig['route_prefix']]);
    }

    private function getCategoryConfig($contentType)
    {
        return $this->bundle['categories'][$contentType];
    }
}
