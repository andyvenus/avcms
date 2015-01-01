<?php
/**
 * User: Andy
 * Date: 15/12/14
 * Time: 18:42
 */

namespace AV\FileHandler;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CurlFileHandler extends FileHandlerBase
{
    public function validateFile($fileUrl, $file)
    {
        /*
        if ($this->maxSize !== null && $uploadedFile->getSize() > $this->maxSize) {
            $this->setError("File size must not be bigger than {max_size}mb", ['max_size' => $this->maxSize]);
            return false;
        }*/

        $mimeGetter = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $mimeGetter->buffer($file);

        if ($this->acceptedFileTypes !== null) {
            foreach ($this->acceptedFileTypes as $extension => $mimeTypes) {
                $mimeTypes = (array) $mimeTypes;

                if ($extension === pathinfo($fileUrl, PATHINFO_EXTENSION) && in_array($mimeType, $mimeTypes)) {
                    $mimeTypeValid = true;
                }
            }

            if (!isset($mimeTypeValid)) {
                $this->setFileTypeError();
                return false;
            }
        }

        return true;
    }

    public function moveFile($fileUrl, $file, $path, $filename = null, $fileExistsStrategy = self::EXISTS_NUMBER)
    {
        if ($this->validateFile($fileUrl, $file) === false) {
            return false;
        }

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        if ($filename === null) {
            $filename = basename($fileUrl);
        }

        $fullPath = $newPath = $path . '/' . $filename;

        if (file_exists($fullPath)) {
            if ($fileExistsStrategy === self::EXISTS_NUMBER) {
                $number = 1;

                while (file_exists($newPath)) {
                    $info = pathinfo($fullPath);
                    $newPath = $info['dirname'] . '/'
                        . $info['filename'] . $number
                        . '.' . $info['extension'];
                    $number++;
                }

                $fullPath = $newPath;
            }
            elseif ($fileExistsStrategy === self::EXISTS_FAIL) {
                $this->setError('File already exists');
                return false;
            }
        }

        file_put_contents($fullPath, $file);

        return $fullPath;
    }
}
