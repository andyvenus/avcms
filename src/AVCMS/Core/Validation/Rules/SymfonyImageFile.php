<?php
/**
 * User: Andy
 * Date: 08/10/2014
 * Time: 14:04
 */

namespace AVCMS\Core\Validation\Rules;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SymfonyImageFile extends Rule
{
    protected $error = 'The file provided for {param_name} is not a valid image file';

    public function __construct($width = null, $height = null)
    {
        $this->width = $width;
        $this->height = $height;
    }

    function assert($param)
    {
        if (!$param instanceof UploadedFile) {
            return false;
        }

        $imageSize = getimagesize($param->getPathname());
        if (!$imageSize[0]) {
            return false;
        }

        return true;
    }
}