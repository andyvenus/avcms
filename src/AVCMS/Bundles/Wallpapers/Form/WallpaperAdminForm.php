<?php

namespace AVCMS\Bundles\Wallpapers\Form;

use AVCMS\Bundles\Admin\Form\AdminContentForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\FileUpload\Form\FileSelectFields;

class WallpaperAdminForm extends AdminContentForm
{
    public function __construct($itemId, CategoryChoicesProvider $categoryChoicesProvider)
    {
        new FileSelectFields($this, 'admin/wallpapers/find-files', 'admin/wallpapers/upload');

        $this->add('name', 'text', array(
            'label' => 'Name',
            'attr' => [
                'data-slug-target' => 'slug'
            ]
        ));
        
        $this->add('description', 'textarea', array(
            'label' => 'Description',
        ));

        $this->add('tags', 'text', array(
            'label' => 'Tags'
        ));

        $this->add('category_id', 'select', array(
            'label' => 'Category',
            'choices_provider' => $categoryChoicesProvider
        ));

        
        $this->add('featured', 'checkbox', array(
            'label' => 'Featured',
        ));

        parent::__construct($itemId);

        /*
       $this->add('crop_type', 'select', array(
           'label' => 'Crop Type',
       ));

      $this->add('crop_position', 'select', array(
           'label' => 'Crop Position'
       ));*/
    }
}
