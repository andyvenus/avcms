<?php
/**
 * User: Andy
 * Date: 15/12/14
 * Time: 18:42
 */

namespace AV\FileHandler;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFileHandler extends FileHandlerBase
{
    public function validateFile(UploadedFile $uploadedFile)
    {
        if ($this->maxSize !== null && $uploadedFile->getSize() > $this->maxSize) {
            $this->setError("File size must not be bigger than {max_size}mb", ['max_size' => $this->maxSize]);
            return false;
        }

        if ($this->acceptedFileTypes !== null) {
            foreach ($this->acceptedFileTypes as $extension => $mimeTypes) {
                $mimeTypes = (array) $mimeTypes;

                if ($extension === $uploadedFile->guessClientExtension() && in_array($uploadedFile->getMimeType(), $mimeTypes)) {
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

    public function moveFile(UploadedFile $file, $path, $filename = null, $fileExistsStrategy = self::EXISTS_NUMBER)
    {
        if ($this->validateFile($file) === false) {
            return false;
        }

        if ($filename === null) {
            $filename = $file->getClientOriginalName();
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

        try {
            $file->move($path, basename($fullPath));
        }
        catch (FileException $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return $fullPath;
    }
}
