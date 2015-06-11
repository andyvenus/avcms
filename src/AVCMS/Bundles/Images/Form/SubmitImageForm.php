<?php
/**
 * User: Andy
 * Date: 19/01/15
 * Time: 11:10
 */

namespace AVCMS\Bundles\Images\Form;

use AV\Form\FormBlueprint;
use AV\Validation\Rules\ExactValue;
use AV\Validation\Validator;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;

class SubmitImageForm extends FormBlueprint
{
    public function __construct(CategoryChoicesProvider $categoryChoices)
    {
        $this->add('files[]', 'file', [
            'label' => 'Image Files',
            'help' => 'You can select multiple files if you want',
            'attr' => [
                'multiple' => 'multiple'
            ]
        ]);

        $this->add('name', 'text', [
            'label' => 'Name',
            'required' => true
        ]);

        $this->add('description', 'textarea', [
            'label' => 'Description'
        ]);

        $this->add('category_id', 'select', [
            'label' => 'Category',
            'choices_provider' => $categoryChoices,
            'strict' => true
        ]);

        $this->add('permission', 'checkbox', [
            'label' => 'I am the copyright owner of this image or have permission from the copyright owner'
        ]);
    }

    public function getValidationRules(Validator $validator)
    {
        //$validator->addRule('file', new SymfonyFile('50000000', ['swf' => ['application/x-shockwave-flash'], 'unity3d' => 'application/vnd.unity']));

        $validator->addRule('permission', new ExactValue(1), 'You must check the permissions field');
    }
}
