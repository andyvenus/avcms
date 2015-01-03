<?php
/**
 * User: Andy
 * Date: 03/01/15
 * Time: 17:52
 */

namespace AVCMS\Bundles\Wallpapers\Form;

use AV\Form\FormBlueprint;

class BulkUploadForm extends FormBlueprint
{
    public function __construct(RecursiveDirectoryChoicesProvider $choicesProvider)
    {
        $this->add('folder', 'select', [
            'choices_provider' => $choicesProvider,
            'label' => 'Folder'
        ]);

        $this->add('file', 'file', [
            'label' => 'Files',
            'field_template' => '@Wallpapers/form/bulk_upload_field.twig'
        ]);
    }
}
