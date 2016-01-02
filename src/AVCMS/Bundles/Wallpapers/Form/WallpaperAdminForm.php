<?php

namespace AVCMS\Bundles\Wallpapers\Form;

use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;
use AVCMS\Bundles\Admin\Form\AdminContentForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\FileUpload\Form\FileSelectFields;

class WallpaperAdminForm extends AdminContentForm
{
    protected $itemId;

    protected $import;

    public function __construct($itemId, CategoryChoicesProvider $categoryChoicesProvider, $import = false)
    {
        $this->itemId = $itemId;
        $this->import = $import;

        if ($import === false) {
            new FileSelectFields($this, 'admin/wallpapers/find-files', 'admin/wallpapers/upload', 'admin/wallpapers/grab-file', 'file', 'wallpaper_file');
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
            'attr' => [
                'rows' => '6'
            ]
        ));

        $this->add('tags', 'text', array(
            'label' => 'Tags'
        ));

        $this->add('category_id', 'select', array(
            'label' => 'Category',
            'choices_provider' => $categoryChoicesProvider,
            'choices_translate' => false
        ));

        $this->add('submitter_id', 'text', array(
            'label' => 'Credited User',
            'attr' => array(
                'class' => 'user_selector no_select2'
            )
        ));

        if ($itemId != 0) {
            $this->add('hits', 'text', [
                'label' => 'Total Hits',
                'section' => 'stats',
            ]);

            $this->add('total_downloads', 'text', [
                'label' => 'Total Downloads',
                'section' => "stats"
            ]);
        }

        parent::__construct($itemId);

        if ($import === true) {
            $this->remove('slug');
        }
    }

    public function getValidationRules(Validator $validator)
    {
        if ($this->import === false) {
            $validator->addRule('slug', new MustNotExist('AVCMS\Bundles\Wallpapers\Model\Wallpapers', 'slug', $this->itemId), 'The URL Slug must be unique, slug already in use');
        }
    }
}
