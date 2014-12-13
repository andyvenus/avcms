<?php

namespace AVCMS\Bundles\Wallpapers\Form;

use AV\Form\FormBlueprint;

class WallpaperAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name',
        ));
        
        $this->add('description', 'textarea', array(
            'label' => 'Description',
        ));
        
        $this->add('file', 'text', array(
            'label' => 'File',
        ));
        
        $this->add('category', 'select', array(
            'label' => 'Category',
        ));
        
        $this->add('published', 'checkbox', array(
            'label' => 'Published',
        ));
        
        $this->add('featured', 'checkbox', array(
            'label' => 'Featured',
        ));
        
        $this->add('slug', 'text', array(
            'label' => 'Slug',
        ));
        
        $this->add('crop_type', 'select', array(
            'label' => 'Crop Type',
        ));
        
        $this->add('crop_vertical', 'radio', array(
            'label' => 'Crop Vertical',
        ));
        
        $this->add('crop_horizontal', 'radio', array(
            'label' => 'Crop Horizontal',
        ));
    }
}