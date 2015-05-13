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
        new FileSelectFields($this, 'admin/images/find-files', 'admin/images/upload', 'admin/images/grab-file', 'images[new][file]', 'images[new]', [], null, 'URL', 'Image', true);

        foreach ($files as $file) {
            new FileSelectFields($this, 'admin/images/find-files', 'admin/images/upload', 'admin/images/grab-file', "images[{$file->getId()}][file]", "images[{$file->getId()}]", [], null, 'URL', 'Image');
        }
    }
}
