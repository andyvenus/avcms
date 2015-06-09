<?php
/**
 * User: Andy
 * Date: 03/01/15
 * Time: 17:52
 */

namespace AVCMS\Bundles\BulkUpload\Form;

use AV\Form\FormBlueprint;

class BulkUploadForm extends FormBlueprint
{
    public function __construct(RecursiveDirectoryChoicesProvider $choicesProvider)
    {
        $this->add('folder', 'select', [
            'choices_provider' => $choicesProvider,
            'label' => 'Folder'
        ]);

        $this->add('existing_files', 'radio', [
            'choices' => [
                'number' => 'Rename (append number)',
                'fail' => "Don't upload",
                'overwrite' => 'Overwrite'
            ],
            'label' => 'If file exists',
            'default' => 'number'
        ]);

        $this->add('file', 'file', [
            'label' => 'Files',
            'field_template' => '@BulkUpload/form/bulk_upload_field.twig'
        ]);
    }
}
