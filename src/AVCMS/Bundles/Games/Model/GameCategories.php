<?php

namespace AVCMS\Bundles\Games\Model;

use AVCMS\Bundles\Categories\Model\Categories;

class GameCategories extends Categories
{
    public function getTable()
    {
        return 'game_categories';
    }

    public function getSingular()
    {
        return 'category';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Games\Model\GameCategory';
    }
}
