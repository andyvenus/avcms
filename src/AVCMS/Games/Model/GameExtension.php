<?php
/**
 * User: Andy
 * Date: 19/02/2014
 * Time: 19:01
 */

namespace AVCMS\Games\Model;

use AVCMS\Core\Model\ExtensionEntity;
use AVCMS\Core\Validation\Rules\Length;
use AVCMS\Core\Validation\Validator;

class GameExtension extends ExtensionEntity
{
    public function setSomething($a) {
        $this->setData('something', $a);
    }

    public function getSomething() {
        return $this->data('something');
    }

    public function validationRules(Validator $validator)
    {
        $validator->addRule('something', new Length(15), 'The WOOP field must be at least 15 characters long', true);
    }

    public function getPrefix()
    {
        return 'testone';
    }
} 