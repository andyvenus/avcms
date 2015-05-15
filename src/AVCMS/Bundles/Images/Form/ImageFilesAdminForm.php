<?php
/**
 * User: Andy
 * Date: 11/05/15
 * Time: 19:53
 */

namespace AVCMS\Bundles\Images\Form;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\FileUpload\Form\FileSelectFields;

class ImageFilesAdminForm extends FormBlueprint
{
    public function __construct(array $files)
    {
        new FileSelectFields($this, 'admin/images/find-files', 'admin/images/upload', 'admin/images/grab-file', 'images[new-files][file]', 'images[new-files]', [], null, 'URL', 'Image', true);

        $this->add('images[new-files][caption]', 'text', ['label' => 'Caption']);

        foreach ($files as $file) {
            new FileSelectFields($this, 'admin/images/find-files', 'admin/images/upload', 'admin/images/grab-file', "images[{$file->getId()}][file]", "images[{$file->getId()}]", [], null, 'URL', 'Image');

            $this->add("images[{$file->getId()}][caption]", 'text', ['label' => 'Caption']);
        }
    }
}
