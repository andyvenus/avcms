<?php
/**
 * User: Andy
 * Date: 20/12/14
 * Time: 12:09
 */

namespace AVCMS\Bundles\Categories\Model;

use AV\Model\Model;

abstract class Categories extends Model
{
    const PARENTS = 'parents';

    const CHILDREN = 'children';

    protected $textIdentifierColumn = 'slug';

    public function getEntity()
    {
        return 'AVCMS\Bundles\Categories\Model\Category';
    }

    public function getCategories($nested = false, Category $offsetCategory = null, $offsetGet = self::CHILDREN)
    {
        $q = $this->query()->orderBy('order');

        if ($offsetCategory !== null) {
            if ($offsetGet == self::PARENTS && count($offsetCategory->getParents())) {
                $q->whereIn('id', $offsetCategory->getParents());
            }
            elseif (count($offsetCategory->getChildren())) {
                $q->whereIn('id', $offsetCategory->getChildren());
            }
            else {
                return [];
            }
        }

        $categories = $q->get();

        if ($nested === false) {
            return $categories;
        }

        foreach ($categories as $category) {
            if ($parent = $category->getParent()) {
                $subCategories = [];
                if (isset($categories[$parent]->subCategories)) {
                    $subCategories = $categories[$parent]->subCategories;
                }

                array_push($subCategories, $category);

                if (isset($categories[$parent])) {
                    $categories[$parent]->subCategories = $subCategories;
                }
            }
        }

        foreach ($categories as $id => $category) {
            if ($category->getParent() && ($offsetCategory === null || $offsetCategory->getId() !== $category->getParent())) {
                unset($categories[$id]);
            }
        }

        return $categories;
    }

    public function getParentCategories()
    {
        $categories = $this->query()->orderBy('order')->whereNull('parent')->get();

        return $categories;
    }

    public function getFullCategory($slug)
    {
        $category = $this->find()->slug($slug)->first();

        if (!$category) {
            return null;
        }

        if ($category->getParents()) {
            $category->parents = $this->getCategories(false, $category, self::PARENTS);
        }

        if ($category->getChildren()) {
            $category->subcategories = $this->getCategories(true, $category, self::CHILDREN);
        }
        
        return $category;
    }
}
