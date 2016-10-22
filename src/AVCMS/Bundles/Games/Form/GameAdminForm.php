<?php

namespace AVCMS\Bundles\Games\Form;

use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;
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

        new FileSelectFields($this, 'admin/games/find-files', 'admin/games/upload', 'admin/games/grab-file', 'file', 'game_file', ['embed_code' => 'HTML'], $selectedField, 'URL');

        $this->add('embed_code', 'textarea', array(
            'label' => 'Embed Code',
        ));

        $this->add('embed_type', 'radio', array(
            'label' => 'Embed Type',
            'choices' => [
                'embed' => 'Embed',
                'link' => 'Open in new window'
            ],
            'default' => 'embed'
        ));

        $this->add('width', 'text', array(
            'label' => 'Width',
        ));

        $this->add('height', 'text', array(
            'label' => 'Height',
        ));

        new FileSelectFields($this, 'admin/games/find-files', 'admin/games/upload', 'admin/games/grab-file', 'thumbnail', 'game_thumbnail', [], null, 'URL');

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
        
        $this->add('instructions', 'textarea', array(
            'label' => 'Instructions',
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
            'help' => 'Show an advert before the game is loaded',
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

        if ($game->getId() != 0) {
            $this->add('hits', 'text', [
                'label' => 'Total Times Played',
                'section' => 'stats',
            ]);
        }

        parent::__construct($game->getId() ?: 0);
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('slug', new MustNotExist('AVCMS\Bundles\Games\Model\Games', 'slug', $this->itemId), 'The URL Slug must be unique, slug already in use');
    }
}
