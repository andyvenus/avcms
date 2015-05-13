<?php
/**
 * User: Andy
 * Date: 19/01/15
 * Time: 11:10
 */

namespace AVCMS\Bundles\Images\Form;

use AV\FileHandler\UploadedFileHandler;
use AV\Form\FormBlueprint;
use AV\Validation\Rules\ExactValue;
use AV\Validation\Rules\SymfonyFile;
use AV\Validation\Validator;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;

class SubmitImageForm extends FormBlueprint
{
    public function __construct(CategoryChoicesProvider $categoryChoices)
    {
        $this->add('file', 'file', [
            'label' => 'Image File'
        ]);

        $this->add('thumbnail', 'file', [
            'label' => 'Image Image'
        ]);

        $this->add('name', 'text', [
            'label' => 'Name',
            'required' => true
        ]);

        $this->add('description', 'textarea', [
            'label' => 'Description'
        ]);

        $this->add('instructions', 'textarea', [
            'label' => 'Instructions'
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
        $validator->addRule('file', new SymfonyFile('50000000', ['swf' => ['application/x-shockwave-flash'], 'unity3d' => 'application/vnd.unity']));

        $validator->addRule('thumbnail', new SymfonyFile('1500000', UploadedFileHandler::getImageFiletypes()));

        $validator->addRule('permission', new ExactValue(1), 'You must check the permissions field');
    }
}
