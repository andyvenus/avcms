<?php

namespace Calendar\Model;

use AVCMS\Model\Model as BaseModel;
use Calendar\Model\Game;

class GamesModel extends BaseModel {
    protected $returns = 'Calendar\Model\Game';
    protected $table = 'avms_games';

    public function category($category_id) {
        $this->where('category_id', '=', $category_id);

        return $this;
    }
}