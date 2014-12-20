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

    public function __construct(Categories $model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    public function getChoices()
    {
        $choices = [];
        foreach ($this->model->getAllCategories() as $category) {
            $choices[$category->getId()] = $category->getParent() ? ' - '.$category->getName() : $category->getName();
        }

        return $choices;
    }
}
