<?php

namespace AVCMS\Bundles\Wallpapers\Form;

use AVCMS\Bundles\Admin\Form\AdminContentForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\FileUpload\Form\FileSelectFields;

class WallpaperAdminForm extends AdminContentForm
{
    public function __construct($itemId, CategoryChoicesProvider $categoryChoicesProvider, $import = false)
    {
        if ($import === false) {
            new FileSelectFields($this, 'admin/wallpapers/find-files', 'admin/wallpapers/upload', 'admin/wallpapers/grab-file');
        }

        $this->add('resize_type', 'radio', [
            'label' => 'Resize Method',
            'choices' => [
                'crop' => 'Crop',
                'stretch' => 'Stretch',
                'original' => 'Don\'t resize (only offer original resolution)'
            ],
            'default' => 'crop'
        ]);

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

        if ($import === true) {
            $this->remove('slug');
        }
    }
}
