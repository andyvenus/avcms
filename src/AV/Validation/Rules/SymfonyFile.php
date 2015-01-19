<?php
/**
 * User: Andy
 * Date: 08/10/2014
 * Time: 12:32
 */

namespace AV\Validation\Rules;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SymfonyFile extends Rule
{
    protected $tooBigError = "File uploaded for '{param_name}' is too big, the file must be no larger than %s";

    protected $wrongTypeError = "File uploaded for '{param_name}' must be one of the following file types: %s";

    protected $maxFileSize;

    protected $mimeTypes;

    protected $unit;

    public function __construct($maxFileSize = null, $mimeTypes = null, $displayUnit = 'MB')
    {
        $this->maxFileSize = $maxFileSize;
        $this->mimeTypes = $this->setMimeTypes($mimeTypes);
        $this->unit = $displayUnit;
    }

    public function assert($param)
    {
        if (!$param instanceof UploadedFile) {
            return false;
        }

        if ($this->maxFileSize && $param->getSize() > $this->maxFileSize) {
            $this->setError(sprintf($this->tooBigError, $this->byteFormat($this->maxFileSize, $this->unit)));
            return false;
        }

        if ($this->mimeTypes && (!isset($this->mimeTypes[$param->getClientOriginalExtension()]) || !in_array($param->getMimeType(), $this->mimeTypes[$param->getClientOriginalExtension()]))) {
            $friendlyFileTypes = array_keys($this->mimeTypes);

            $friendlyFileTypesStr = implode(', ', $friendlyFileTypes);

            $this->setError(sprintf($this->wrongTypeError, $friendlyFileTypesStr));

            return false;
        }

        return true;
    }

    protected function byteFormat($bytes, $unit = "", $decimals = 2) {
        $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4,
            'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);

        $value = 0;
        if ($bytes > 0) {
            // Generate automatic prefix by bytes
            // If wrong prefix given
            if (!array_key_exists($unit, $units)) {
                $pow = floor(log($bytes)/log(1024));
                $unit = array_search($pow, $units);
            }

            // Calculate byte value by prefix
            $value = ($bytes/pow(1024,floor($units[$unit])));
        }

        // If decimals is not numeric or decimals is less than 0
        // then set default value
        if (!is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }

        // Format output
        return sprintf('%.' . $decimals . 'f '.$unit, $value);
    }

    protected function setMimeTypes(array $mimeTypes)
    {
        $processedMimeTypes = [];
        foreach ($mimeTypes as $extension => $mimeType) {
            $processedMimeTypes[$extension] = (array) $mimeType;
        }

        return $processedMimeTypes;
    }
}
