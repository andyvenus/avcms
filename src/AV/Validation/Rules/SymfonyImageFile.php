<?php
/**
 * User: Andy
 * Date: 08/10/2014
 * Time: 14:04
 */

namespace AV\Validation\Rules;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SymfonyImageFile extends Rule
{
    const MAX_SIZE = 'MAX';

    const MIN_SIZE = 'MIN';

    protected $error = 'The file provided for {param_name} is not a valid image file';

    protected $width;

    protected $height;

    protected $constraint;

    public function __construct($width = null, $height = null, $constraint = self::MAX_SIZE)
    {
        $this->width = $width;
        $this->height = $height;
        $this->constraint = $constraint;
    }

    public function assert($param)
    {
        if (!$param instanceof UploadedFile) {
            return false;
        }

        $imageSize = getimagesize($param->getPathname());
        if (!$imageSize[0]) {
            return false;
        }

        if ($this->width !== null && $this->height !== null) {
            if ($this->constraint === self::MAX_SIZE && ($imageSize[0] > $this->width || $imageSize[1] > $this->height)) {
                $this->setError('The uploaded image cannot be a larger resolution than {width}x{height}', ['width' => $this->width, 'height' => $this->height]);
                return false;
            }
            elseif ($this->constraint === self::MIN_SIZE && ($imageSize[0] < $this->width || $imageSize[1] < $this->height)) {
                $this->setError('The uploaded image cannot be a smaller resolution than {width}x{height}', ['width' => $this->width, 'height' => $this->height]);
                return false;
            }
        }

        return true;
    }
}
