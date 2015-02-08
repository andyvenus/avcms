<?php

namespace AVCMS\Bundles\Games\Form;

use AVCMS\Bundles\Admin\Form\AdminContentForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\FileUpload\Form\FileSelectFields;
use AVCMS\Bundles\Games\Model\Game;

class GameAdminForm extends AdminContentForm
{
    public function __construct(Game $game, CategoryChoicesProvider $categoryChoicesProvider)
    {
        $selectedField = 'file';
        if ($game->getEmbedCode()) {
            $selectedField = 'embed_code';
        }

        new FileSelectFields($this, 'admin/games/find-files', 'admin/games/upload', 'admin/games/grab-file', 'file', 'game_file', ['embed_code' => 'HTML'], $selectedField);

        $this->add('embed_code', 'textarea', array(
            'label' => 'Embed Code',
        ));

        new FileSelectFields($this, 'admin/games/find-files', 'admin/games/upload', 'admin/games/grab-file', 'thumbnail', 'game_thumbnail');

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
        
        $this->add('category_id', 'select', array(
            'label' => 'Category',
            'choices_provider' => $categoryChoicesProvider,
            'choices_translate' => false
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

        $this->add('tags', 'text', array(
            'label' => 'Tags'
        ));

        $this->add('advert_id', 'select', array(
            'label' => 'Advert',
            'choices' => ['0' => 'None'],
            'choices_provider_service' => 'adverts.choices_provider',
            'translate_choices' => false
        ));
        
        $this->add('submitter_id', 'text', array(
            'label' => 'Credited User',
            'attr' => array(
                'class' => 'user_selector no_select2'
            )
        ));

        parent::__construct($game->getId() ?: 0);
    }
}