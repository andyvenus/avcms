<?php
/**
 * User: Andy
 * Date: 14/12/14
 * Time: 16:50
 */

namespace AVCMS\Bundles\FileUpload\Form;

use AV\Form\FormBlueprintInterface;

class FileSelectFields {
    public function __construct(FormBlueprintInterface $formBlueprint, $fileSelectUrl, $uploadUrl)
    {
        $formBlueprint->add('file_type', 'radio', [
            'label' => 'File Type',
            'choices' => [
                'file' => 'Path',
                'find' => 'Find',
                'upload' => 'Upload',
                'grab' => 'Grab'
            ],
            'default' => 'file'
        ]);

        $formBlueprint->add('file', 'text', array(
            'label' => 'File Path',
        ));

        $formBlueprint->add('find', 'text', array(
            'label' => 'Find File',
            'attr' => array(
                'class' => 'file_selector_dropdown no_select2',
                'data-file-select-url' => $fileSelectUrl
            )
        ));

        $formBlueprint->add('upload', 'file', [
            'label' => 'Upload File',
            'field_template' => '@FileUpload/file_upload_field.twig',
            'attr' => [
                'data-upload-url' => $uploadUrl
            ]
        ]);

        $formBlueprint->add('grab', 'text', [
            'label' => 'Grab File'
        ]);
    }
}
