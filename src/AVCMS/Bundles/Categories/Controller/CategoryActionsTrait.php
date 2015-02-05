<?php
/**
 * User: Andy
 * Date: 20/12/14
 * Time: 13:10
 */

namespace AVCMS\Bundles\Categories\Controller;

use AV\Form\FormBlueprint;
use AV\Form\FormError;
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

        return new Response($this->renderAdminSection('@Categories/admin/manage_categories.twig', ['categories' => $categories, 'route_prefix' => $categoryConfig['route_prefix']]));
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

                $previousParent = $category->getParent();

                $category->setParent($parent);
                $category->setOrder($i);

                $model->update($category);

                if ($previousParent !== $parent) {
                    $contentModel = $this->model($categoryConfig['content_model']);

                    $contentModel->query()->where('category_id', $category->getId())->update(['category_parent_id' => $parent]);
                }
            }
        }

        return new JsonResponse(array('success' => true));
    }

    public function editCategoryAction(Request $request, $contentType)
    {
        $categoryConfig = $this->getCategoryConfig($contentType);
        $model = $this->model($categoryConfig['model']);

        $formBlueprint = $this->getCategoryForm();

        return $this->handleEdit($request, $model, $formBlueprint, $categoryConfig['route_prefix'].'manage_categories', '@Categories/admin/edit_category.twig', ['route_prefix' => $categoryConfig['route_prefix']]);
    }

    /**
     * @return FormBlueprint
     */
    abstract protected function getCategoryForm();

    public function deleteCategoryAction(Request $request, $contentType)
    {
        if (!$this->checkCsrfToken($request)) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $categoryConfig = $this->getCategoryConfig($contentType);
        $model = $this->model($categoryConfig['model']);
        $contentModel = $this->model($categoryConfig['content_model']);

        $category = $model->getOne($request->get('id'));

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $moveItemsForm = new FormBlueprint();
        $moveItemsForm->setName('category_delete_form');

        $moveItemsForm->add('new_category', 'select', [
            'label' => "Move existing items to",
            'choices_provider' => new CategoryChoicesProvider($model, true, false, $request->get('id'))
        ]);

        $moveItemsForm->add('delete_subcategories', 'checkbox', [
            'label' => "Delete Sub-Categories",
        ]);

        $moveItemsForm->setAction($this->generateUrl($categoryConfig['route_prefix'].'admin_delete_category', ['id' => $request->get('id')]));

        $form = $this->buildForm($moveItemsForm, $request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $deleteIds = [$category->getId()];
                $subCategories = $model->getSubCategories($category->getId());

                $newCategory = $model->getOne($form->getData('new_category'));

                foreach ($subCategories as $subCategory) {
                    if ($form->getData('delete_subcategories')) {
                        if ($newCategory->getId() === $subCategory->getId()) {
                            $formErrors[] = new FormError('new_category', "Can't move items into a category that is about to be deleted");
                            break;
                        }

                        $deleteIds[] = $subCategory->getId();
                    } else {
                        $subCategory->setParent(null);
                        $model->save($subCategory);
                        $contentModel->query()->where('category_id', $subCategory->getId())->update(['category_parent_id' => null]);
                    }
                }

                if (!isset($formErrors)) {
                    $contentModel->query()->whereIn('category_id', $deleteIds)->update(['category_id' => $newCategory->getId(), 'category_parent_id' => $newCategory->getParent()]);

                    $model->query()->whereIn('id', $deleteIds)->delete();
                }
                else {
                    $form->addCustomErrors($formErrors);
                }
            }

            return new JsonResponse(['form' => $form->createView()->getJsonResponseData()]);
        }

        return new JsonResponse(['html' => $this->render('@CmsFoundation/modal_form.twig', ['form' => $form->createView(), 'modal_title' => 'Delete Category'])]);
    }

    private function getCategoryConfig($contentType)
    {
        return $this->bundle['categories'][$contentType];
    }
}
