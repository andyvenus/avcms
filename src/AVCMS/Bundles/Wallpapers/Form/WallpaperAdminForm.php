<?php

namespace AVCMS\Bundles\Wallpapers\Form;

use AVCMS\Bundles\Admin\Form\AdminContentForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\FileUpload\Form\FileSelectFields;

class WallpaperAdminForm extends AdminContentForm
{
    public function __construct($itemId, CategoryChoicesProvider $categoryChoicesProvider, $fileSelect = true)
    {
        if ($fileSelect === true) {
            new FileSelectFields($this, 'admin/wallpapers/find-files', 'admin/wallpapers/upload', 'admin/wallpapers/grab-file');
        }

        $this->add('crop_position', 'select', [
            'choices' => [
                'top-left' => 'Top-Left',
                'top' => 'Top-Center',
                'top-right' => 'Top-Right',
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
                'bottom-left' => 'Bottom-Left',
                'bottom' => 'Bottom-Center',
                'bottom-right' => 'Bottom-Right'
            ],
            'label' => 'Crop Position',
            'default' => 'center',
            'strict' => true,
        ]);

        $this->add('name', 'text', array(
            'label' => 'Name',
            'attr' => [
                'data-slug-target' => 'slug'
            ],
            'required' => true
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
