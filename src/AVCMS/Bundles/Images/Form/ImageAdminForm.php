<?php

namespace AVCMS\Bundles\Images\Form;

use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;
use AVCMS\Bundles\Admin\Form\AdminContentForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Images\Model\Image;

class ImageAdminForm extends AdminContentForm
{
    /**
     * @param Image $image
     * @param CategoryChoicesProvider $categoryChoicesProvider
     * @throws \Exception
     */
    public function __construct(Image $image, CategoryChoicesProvider $categoryChoicesProvider)
    {
        $this->add('name', 'text', array(
            'label' => 'Name',
            'attr' => [
                'data-slug-target' => 'slug'
            ],
            'required' => true
        ));
        
        $this->add('description', 'textarea', array(
            'label' => 'Description',
            'attr' => [
                'rows' => '6'
            ]
        ));

        $this->add('category_id', 'select', array(
            'label' => 'Category',
            'choices_provider' => $categoryChoicesProvider,
            'choices_translate' => false
        ));

        $this->add('tags', 'text', array(
            'label' => 'Tags'
        ));

        $this->add('advert_id', 'select', array(
            'label' => 'Advert',
            'help' => 'Show an advert before the image is loaded',
            'choices' => ['0' => 'Default'],
            'choices_provider_service' => 'adverts.choices_provider',
            'translate_choices' => false
        ));
        
        $this->add('submitter_id', 'text', array(
            'label' => 'Credited User',
            'attr' => array(
                'class' => 'user_selector no_select2'
            )
        ));

        parent::__construct($image->getId() ?: 0);
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('slug', new MustNotExist('AVCMS\Bundles\Images\Model\Images', 'slug', $this->itemId), 'The URL Slug must be unique, slug already in use');
    }
}
