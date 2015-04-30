<?php
/**
 * User: Andy
 * Date: 19/01/15
 * Time: 11:10
 */

namespace AVCMS\Bundles\Wallpapers\Form;

use AV\FileHandler\UploadedFileHandler;
use AV\Form\FormBlueprint;
use AV\Validation\Rules\SymfonyImageFile;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;

class SubmitWallpaperForm extends FormBlueprint
{
    public function __construct(CategoryChoicesProvider $categoryChoices)
    {
        $this->add('file', 'file', [
            'label' => 'Wallpaper Image',
            'required' => true,
            'validation' => [
                ['rule' => 'SymfonyImageFile', 'arguments' => [1024, 768, SymfonyImageFile::MIN_SIZE]],
                ['rule' => 'SymfonyFile', 'arguments' => ['10000000', UploadedFileHandler::getImageFiletypes()]]
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
            'label' => 'I am the copyright owner of this image or have permission from the copyright owner',
            'validation' => [
                ['rule' => 'ExactValue', 'arguments' => [1], 'error_message' => 'You must check the permissions field']
            ]
        ]);
    }
}
