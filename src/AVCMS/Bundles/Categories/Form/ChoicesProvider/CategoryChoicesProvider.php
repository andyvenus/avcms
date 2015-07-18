<?php
/**
 * User: Andy
 * Date: 20/12/14
 * Time: 18:28
 */

namespace AVCMS\Bundles\Categories\Form\ChoicesProvider;

use AV\Form\ChoicesProviderInterface;
use AVCMS\Bundles\Categories\Model\Categories;
use AVCMS\Bundles\Categories\Model\Category;

class CategoryChoicesProvider implements ChoicesProviderInterface
{
    protected $model;

    protected $parentCategory;

    protected $allowNone;

    protected $exclude;

    public function __construct(Categories $model, $allowNone = false, $exclude = [], Category $parentCategory = null)
    {
        $this->model = $model;
        $this->parentCategory = $parentCategory;
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
            $choices[0] = 'All Categories';
        }

        $categories = $this->model->getCategories(false, $this->parentCategory);

        foreach ($categories as $category) {
            if (!in_array($category->getId(), $this->exclude)) {
                $dashes = '';

                foreach ($category->getParents() as $parent) {
                    $dashes .= '-';
                }

                if ($dashes) {
                    $dashes .= ' ';
                }

                $choices[$category->getId()] = $dashes.$category->getName();
            }
        }

        return $choices;
    }
}
