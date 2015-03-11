<?php
/**
 * User: Andy
 * Date: 14/12/14
 * Time: 16:50
 */

namespace AVCMS\Bundles\FileUpload\Form;

use AV\Form\FormBlueprintInterface;

class FileSelectFields {
    public function __construct(FormBlueprintInterface $formBlueprint, $fileSelectUrl, $uploadUrl, $grabUrl, $fieldName = 'file', $groupName = 'file', $additionalChoices = [], $defaultSelected = null, $pathLabel = 'Path')
    {
        $defaultSelected = ($defaultSelected ? $defaultSelected : $fieldName);

        $fieldNameUc = ucfirst($fieldName);

        $choices = [
            $fieldName => $pathLabel,
            $groupName.'[find]' => 'Find',
            $groupName.'[upload]' => 'Upload',
            $groupName.'[grab]' => 'Grab'
        ];

        $choices = $choices + $additionalChoices;

        $formBlueprint->add($groupName.'[file_type]', 'radio', [
            'label' => $fieldNameUc.' Type',
            'choices' => $choices,
            'attr' => ['data-file-selector-group' => $groupName, 'data-file-selector-target' => $fieldName],
            'default' => $defaultSelected
        ]);

        $formBlueprint->add($fieldName, 'text', array(
            'label' => $fieldNameUc.' Path',
        ));

        $formBlueprint->add($groupName.'[find]', 'text', array(
            'label' => 'Find '.$fieldNameUc,
            'attr' => array(
                'class' => 'file-selector-dropdown no_select2',
                'data-file-select-url' => $fileSelectUrl
            )
        ));

        $formBlueprint->add($groupName.'[upload]', 'file', [
            'label' => 'Upload '.$fieldNameUc,
            'field_template' => '@FileUpload/file_upload_field.twig',
            'attr' => [
                'data-upload-url' => $uploadUrl
            ]
        ]);

        $formBlueprint->add($groupName.'[grab]', 'text', [
            'label' => 'Grab '.$fieldNameUc.' From URL',
            'field_template' => '@FileUpload/grab_file_field.twig',
            'attr' => array(
                'data-grab-file-url' => $grabUrl
            )
        ]);
    }
}
