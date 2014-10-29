<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:39
 */

namespace AVCMS\Bundles\Blog\Model;

use AV\Model\ContentEntity;
use AV\Validation\Rules\Length;
use AV\Validation\Validator;

class BlogPost extends ContentEntity {
    public function setTitle($value) {
        $this->set('title', $value);
    }

    public function getTitle() {
        return $this->get('title');
    }

    public function setBody($value) {
        $this->set('body', $value);
    }

    public function getBody() {
        return $this->get('body');
    }

    public function setUserId($value) {
        $this->set('user_id', $value);
    }

    public function getUserId() {
        return $this->get('user_id');
    }

    public function validationRules(Validator $validator)
    {
        $validator->addRule('title', new Length(10), 'The title must be 10 chars long', true);
    }
} 