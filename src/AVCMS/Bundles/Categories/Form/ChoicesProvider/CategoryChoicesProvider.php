<?php
/**
 * User: Andy
 * Date: 20/12/14
 * Time: 18:28
 */

namespace AVCMS\Bundles\Categories\Form\ChoicesProvider;

use AV\Form\ChoicesProviderInterface;
use AVCMS\Bundles\Categories\Model\Categories;

class CategoryChoicesProvider implements ChoicesProviderInterface
{
    protected $model;

    protected $subCategories;

    protected $allowNone;

    protected $exclude;

    public function __construct(Categories $model, $subCategories = true, $allowNone = false, $exclude = [])
    {
        $this->model = $model;
        $this->subCategories = $subCategories;
        $this->allowNone = $allowNone;
        $this->exclude = (array) $exclude;
    }

    /**
     * @return array
     */
    public function getChoices()
    {
        $choices = [];

        if ($this->allowNone) {
            $choices[0] = 'All';
        }

        if ($this->subCategories === true) {
            $categories = $this->model->getAllCategories();
        }
        else {
            $categories = $this->model->getParentCategories();
        }

        foreach ($categories as $category) {
            if (!in_array($category->getId(), $this->exclude)) {
                $choices[$category->getId()] = $category->getParent() ? ' - ' . $category->getName() : $category->getName();
            }
        }

        return $choices;
    }
}
