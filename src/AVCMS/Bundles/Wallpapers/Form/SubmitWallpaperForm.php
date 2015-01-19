<?php
/**
 * User: Andy
 * Date: 19/01/15
 * Time: 11:10
 */

namespace AVCMS\Bundles\Wallpapers\Form;

use AV\FileHandler\UploadedFileHandler;
use AV\Form\FormBlueprint;
use AV\Validation\Rules\ExactValue;
use AV\Validation\Rules\SymfonyFile;
use AV\Validation\Rules\SymfonyImageFile;
use AV\Validation\Validator;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;

class SubmitWallpaperForm extends FormBlueprint
{
    public function __construct(CategoryChoicesProvider $categoryChoices)
    {
        $this->add('file', 'file', [
            'label' => 'Wallpaper Image'
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
        $validator->addRule('file', new SymfonyImageFile(1024, 768, SymfonyImageFile::MIN_SIZE));
        $validator->addRule('file', new SymfonyFile('10000000', UploadedFileHandler::getImageFiletypes()));
        $validator->addRule('permission', new ExactValue(1), 'You must check the permissions field');
    }
}
