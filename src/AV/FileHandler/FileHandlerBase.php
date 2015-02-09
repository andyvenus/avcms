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

    const WHITELIST_ERROR = "Files must be one of the following file types: {file_types}";

    const BLACKLIST_ERROR = "Files must NOT be one of the following file types: {file_types}";

    protected $acceptedFileTypes;

    protected $deniedFileTypes;

    protected $maxSize;

    protected $error;

    /**
     * Accepted file types & denied file types can be in the following formats:
     *
     * array (
     *     'png' => 'image/png',                            String, one mime type
     *     'bmp' => ['image/bmp', 'image/x-windows-bmp'],   Array of multiple mime types
     *     'gif' => '*'                                     Use asterisk to accept/deny all mime types
     * );
     *
     * @param array $acceptedFileTypes
     * @param array $deniedFileTypes
     * @param null $maxSize
     */
    public function __construct(array $acceptedFileTypes = null, array $deniedFileTypes = null, $maxSize = null)
    {
        $this->acceptedFileTypes = $acceptedFileTypes;
        $this->deniedFileTypes = $deniedFileTypes;
        $this->maxSize = $maxSize;
    }

    protected function setError($error, $parameters = [])
    {
        $this->error = ['message' => $error, 'parameters' => $parameters];
    }

    public function getTranslatedError(TranslatorInterface $translator)
    {
        if (!isset($this->error)) {
            return null;
        }

        return $translator->trans($this->error['message'], $this->error['parameters']);
    }

    public function getError()
    {
        return $this->error;
    }

    protected function setWhitelistError()
    {
        $friendlyFileTypes = [];
        foreach ($this->acceptedFileTypes as $fileTypeName => $mimeType) {
            $friendlyFileTypes[] = $fileTypeName;
        }

        $friendlyFileTypesStr = implode(', ', $friendlyFileTypes);

        $this->setError(self::WHITELIST_ERROR, ['file_types' => $friendlyFileTypesStr]);
    }

    protected function setBlacklistError()
    {
        $badFileTypes = [];
        foreach ($this->deniedFileTypes as $fileTypeName => $mimeType) {
            $badFileTypes[] = $fileTypeName;
        }

        $badFileTypesStr = implode(', ', $badFileTypes);

        $this->setError(self::BLACKLIST_ERROR, ['file_types' => $badFileTypesStr]);
    }

    protected function checkFileType($fileExtension, $fileMimeType)
    {
        if ($this->acceptedFileTypes !== null) {
            foreach ($this->acceptedFileTypes as $extension => $mimeTypes) {
                $mimeTypes = (array) $mimeTypes;

                if ($extension === $fileExtension && (in_array($fileMimeType, $mimeTypes) || $mimeTypes[0] === '*')) {
                    $mimeTypeValid = true;
                }
            }

            if (!isset($mimeTypeValid)) {
                $this->setWhitelistError();
                return false;
            }
        }

        if ($this->deniedFileTypes !== null) {
            foreach ($this->deniedFileTypes as $extension => $mimeTypes) {
                $mimeTypes = (array) $mimeTypes;

                if ($extension === $fileExtension && (in_array($fileMimeType, $mimeTypes) || $mimeTypes[0] === '*')) {
                    $blacklistError = true;
                }
            }

            if (isset($blacklistError)) {
                $this->setBlacklistError();
                return false;
            }
        }

        return true;
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
