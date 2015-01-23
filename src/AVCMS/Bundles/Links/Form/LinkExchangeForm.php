<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 13:54
 */

namespace AVCMS\Bundles\Links\Form;

use AV\Form\FormBlueprint;
use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;

class LinkExchangeForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('anchor', 'text', array(
            'label' => 'Anchor',
            'required' => true
        ));

        $this->add('url', 'text', array(
            'label' => 'URL',
            'required' => true,
            'transform' => 'url',
        ));

        $this->add('description', 'textarea', array(
            'label' => 'Description',
            'required' => true
        ));
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('url', new MustNotExist('AVCMS\Bundles\Links\Model\Links', 'url'), 'That URL has already been submitted.');
    }
}
