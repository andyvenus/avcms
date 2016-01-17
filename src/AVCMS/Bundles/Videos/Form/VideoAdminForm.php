<?php

namespace AVCMS\Bundles\Videos\Form;

use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;
use AVCMS\Bundles\Admin\Form\AdminContentForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\FileUpload\Form\FileSelectFields;
use AVCMS\Bundles\Videos\Model\Video;

class VideoAdminForm extends AdminContentForm
{
    public function __construct(Video $video, CategoryChoicesProvider $categoryChoicesProvider)
    {
        $this->setName('edit_video');

        $selectedField = 'file';
        if ($video->getEmbedCode()) {
            $selectedField = 'embed_code';
        }

        new FileSelectFields($this, 'admin/videos/find-files', 'admin/videos/upload', 'admin/videos/grab-file', 'file', 'video_file', ['embed_code' => 'HTML'], $selectedField, 'URL', 'Video');

        $this->add('embed_code', 'textarea', array(
            'label' => 'Embed Code',
        ));

        new FileSelectFields($this, 'admin/videos/find-files', 'admin/videos/upload', 'admin/videos/grab-file', 'thumbnail', 'video_thumbnail', [], null, 'URL');

        $this->add('name', 'text', array(
            'label' => 'Title',
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

        $this->add('duration', 'text', [
            'label' => 'Duration',
            'help' => 'Duration in Hours:Minutes:Sections format (examples: 12:23, 1:23:32)'
        ]);

        $this->add('tags', 'text', array(
            'label' => 'Tags'
        ));

        $this->add('advert_id', 'select', array(
            'label' => 'Advert',
            'help' => 'Show an advert before the video is loaded',
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

        $this->add('provider', 'hidden');

        $this->add('provider_id', 'hidden');

        if ($video->getId() != 0) {
            $this->add('hits', 'text', [
                'label' => 'Total Times Watched',
                'section' => 'stats',
            ]);
        }

        parent::__construct($video->getId() ?: 0);
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('slug', new MustNotExist('AVCMS\Bundles\Videos\Model\Videos', 'slug', $this->itemId), 'The URL Slug must be unique, slug already in use');
    }
}
