<?php
/**
 * User: Andy
 * Date: 15/12/14
 * Time: 18:42
 */

namespace AV\FileHandler;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Translation\TranslatorInterface;

class UploadedFileHandler
{
    const EXISTS_NUMBER = 'number';

    const EXISTS_FAIL = 'fail';

    const EXISTS_OVERWRITE = 'overwrite';

    protected $acceptedFileTypes;

    protected $maxSize;

    protected $error;

    public function __construct(array $acceptedFileTypes = null, $maxSize = null)
    {
        $this->acceptedFileTypes = $acceptedFileTypes;
        $this->maxSize = $maxSize;
    }

    public function validateFile(UploadedFile $uploadedFile)
    {
        if ($this->maxSize !== null && $uploadedFile->getSize() > $this->maxSize) {
            $this->setError("File size must not be bigger than {max_size}mb", ['max_size' => $this->maxSize]);
            return false;
        }

        if ($this->acceptedFileTypes !== null) {
            foreach ($this->acceptedFileTypes as $extension => $mimeTypes)
            if (!in_array($uploadedFile->getMimeType(), $mimeTypes)) {
                $friendlyFileTypes = [];
                foreach ($this->maxSize as $fileTypeName => $mimeType) {
                    $friendlyFileTypes[] = $fileTypeName;
                }

                $friendlyFileTypesStr = implode(', ', $friendlyFileTypes);

                $this->setError("Files must be one of the following file types: {file_types}", ['file_types' => $friendlyFileTypesStr]);
                return false;
            }
        }

        return true;
    }

    protected function setError($error, $parameters = [])
    {
        $this->error = ['message' => $error, 'parameters' => $parameters];
    }

    public function getTranslatedError(TranslatorInterface $translator)
    {
        return $translator->trans($this->error['message'], $this->error['parameters']);
    }

    public function getError()
    {
        return $this->error;
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

        return true;
    }
}
