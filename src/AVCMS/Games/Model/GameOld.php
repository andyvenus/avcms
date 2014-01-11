<?php

namespace AVCMS\Games\Model;

use AVCMS\Core\Model\Entity;
use AVCMS\Core\Validation\Rules;
use AVCMS\Core\Validation\Validator;

class Game extends Entity {

    protected $fields = array(
        'id',
        'name',
        'description',
        'url',
        'category_id',
        'category_parent',
        'hits',
        'published',
        'user_submit',
        'width',
        'height',
        'image',
        'import',
        'filetype',
        'instructions',
        'mochi',
        'rating',
        'featured',
        'date_added',
        'advert_id',
        'highscores',
        'mochi_id',
        'seo_url',
        'submitter',
        'html_code',
    );

    protected $relations = array(
        'categories' => 'category'
    );

    public function validationRules(Validator $validator)
    {
        $validator->addRule('description', new Rules\Numeric(), "Holy MOLY THE DESCRIPTION TOO");

        $validator->addRule('name', new Rules\MustNotExist('AVCMS\Games\Model\Games', 'name', $this->id), "That name is already in use");

        return $validator;
    }
}