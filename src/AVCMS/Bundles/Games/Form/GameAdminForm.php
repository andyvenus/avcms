<?php

namespace AVCMS\Bundles\Games\Form;

use AVCMS\Bundles\Admin\Form\AdminContentForm;
use AVCMS\Bundles\FileUpload\Form\FileSelectFields;

class GameAdminForm extends AdminContentForm
{
    public function __construct($itemId)
    {
        new FileSelectFields($this, '123', '123', '123', 'file', 'game_file');

        new FileSelectFields($this, '123', '123', '123', 'thumbnail', 'game_thumbnail');

        $this->add('name', 'text', array(
            'label' => 'Name',
        ));
        
        $this->add('description', 'textarea', array(
            'label' => 'Description',
        ));
        
        $this->add('category_id', 'select', array(
            'label' => 'Category Id',
        ));
        
        $this->add('width', 'text', array(
            'label' => 'Width',
        ));
        
        $this->add('height', 'text', array(
            'label' => 'Height',
        ));
        
        $this->add('instructions', 'textarea', array(
            'label' => 'Instructions',
        ));
        
        $this->add('advert_id', 'select', array(
            'label' => 'Advert Id',
        ));
        
        $this->add('submitter_id', 'select', array(
            'label' => 'Submitter Id',
        ));
        
        $this->add('embed_code', 'textarea', array(
            'label' => 'Embed Code',
        ));

        parent::__construct($itemId);
    }
}
