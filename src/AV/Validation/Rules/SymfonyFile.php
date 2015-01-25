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
        $label = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
        for( $i = 0; $bytes >= 1024 && $i < ( count( $label ) -1 ); $bytes /= 1024, $i++ );

        if ($bytes % 1 === 0) {
            $bytes = $bytes / 1000;
            $i++;
        }

        return( round( $bytes, 2 ) . " " . $label[$i] );
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
