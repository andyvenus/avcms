<?php
/**
 * User: Andy
 * Date: 14/12/14
 * Time: 16:50
 */

namespace AVCMS\Bundles\FileUpload\Form;

use AV\Form\FormBlueprintInterface;

class FileSelectFields {
    public function __construct(FormBlueprintInterface $formBlueprint, $fileSelectUrl, $uploadUrl, $grabUrl, $fieldName = 'file', $groupName = 'wallpaper_file')
    {
        $formBlueprint->add($groupName.'[file_type]', 'radio', [
            'label' => 'File Type',
            'choices' => [
                $fieldName => 'Path',
                $groupName.'[find]' => 'Find',
                $groupName.'[upload]' => 'Upload',
                $groupName.'[grab]' => 'Grab'
            ],
            'attr' => ['data-file-selector-group' => $groupName, 'data-file-selector-target' => $fieldName],
            'default' => $fieldName
        ]);

        $formBlueprint->add($fieldName, 'text', array(
            'label' => 'File Path',
        ));

        $formBlueprint->add($groupName.'[find]', 'text', array(
            'label' => 'Find File',
            'attr' => array(
                'class' => 'file-selector-dropdown no_select2',
                'data-file-select-url' => $fileSelectUrl
            )
        ));

        $formBlueprint->add($groupName.'[upload]', 'file', [
            'label' => 'Upload File',
            'field_template' => '@FileUpload/file_upload_field.twig',
            'attr' => [
                'data-upload-url' => $uploadUrl
            ]
        ]);

        $formBlueprint->add($groupName.'[grab]', 'text', [
            'label' => 'Grab File From URL',
            'field_template' => '@FileUpload/grab_file_field.twig',
            'attr' => array(
                'data-grab-file-url' => $grabUrl
            )
        ]);
    }
}
