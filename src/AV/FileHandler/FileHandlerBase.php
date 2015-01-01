<?php
/**
 * User: Andy
 * Date: 01/01/15
 * Time: 19:47
 */

namespace AV\FileHandler;

use Symfony\Component\Translation\TranslatorInterface;

abstract class FileHandlerBase
{
    const EXISTS_NUMBER = 'number';

    const EXISTS_FAIL = 'fail';

    const EXISTS_OVERWRITE = 'overwrite';

    const FILE_TYPE_ERROR = "Files must be one of the following file types: {file_types}";

    protected $acceptedFileTypes;

    protected $maxSize;

    protected $error;

    public function __construct(array $acceptedFileTypes = null, $maxSize = null)
    {
        $this->acceptedFileTypes = $acceptedFileTypes;
        $this->maxSize = $maxSize;
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

    protected function setFileTypeError()
    {
        $friendlyFileTypes = [];
        foreach ($this->acceptedFileTypes as $fileTypeName => $mimeType) {
            $friendlyFileTypes[] = $fileTypeName;
        }

        $friendlyFileTypesStr = implode(', ', $friendlyFileTypes);

        $this->setError(self::FILE_TYPE_ERROR, ['file_types' => $friendlyFileTypesStr]);
    }

    public static function getImageFiletypes()
    {
        return [
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'bmp' => ['image/bmp', 'image/x-windows-bmp']
        ];
    }
}
