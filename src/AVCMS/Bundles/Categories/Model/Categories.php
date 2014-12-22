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
    public function getEntity()
    {
        return 'AVCMS\Bundles\Categories\Model\Category';
    }

    public function getAllCategories($nested = false)
    {
        $categories = $this->query()->orderBy('parent', 'DESC')->orderBy('order')->get();

        $mainCategories = [];
        $subCategories = [];

        foreach ($categories as $category) {
            if ($parent = $category->getParent()) {
                $subCategories[$parent][] = $category;
            }
            else {
                $mainCategories[] = $category;
                if (isset($subCategories[$category->getId()])) {
                    if ($nested === false) {
                        $mainCategories = array_merge($mainCategories, $subCategories[$category->getId()]);
                    }
                    else {
                        $category->subCategories = $subCategories[$category->getId()];
                    }
                }
            }
        }

        return $mainCategories;
    }

    public function getParentCategories()
    {
        $categories = $this->query()->orderBy('order')->whereNull('parent')->get();

        return $categories;
    }

    public function getSubCategories($parentId)
    {
        return $this->query()->where('parent', $parentId)->get();
    }
}
