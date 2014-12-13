<?php

namespace AVCMS\Bundles\Wallpapers\Form;

use AVCMS\Bundles\Admin\Form\AdminContentForm;

class WallpaperAdminForm extends AdminContentForm
{
    public function __construct($itemId)
    {
        $this->add('file', 'text', array(
            'label' => 'File',
        ));

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

        /*
        $this->add('category', 'select', array(
            'label' => 'Category',
        ));*/

        
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
