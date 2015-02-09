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

        return $this->checkFileType($uploadedFile->guessClientExtension(), $uploadedFile->getMimeType());
    }

    public function moveFile(UploadedFile $file, $destinationPath, $filename = null, $fileExistsStrategy = self::EXISTS_NUMBER)
    {
        if ($this->validateFile($file) === false) {
            return false;
        }

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        if ($filename === null) {
            $filename = $file->getClientOriginalName();
        }

        $fullPath = $newPath = $destinationPath . '/' . $filename;

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
            $file->move($destinationPath, basename($fullPath));
        }
        catch (FileException $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return $fullPath;
    }
}
