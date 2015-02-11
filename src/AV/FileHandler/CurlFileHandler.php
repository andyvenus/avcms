<?php
/**
 * User: Andy
 * Date: 15/12/14
 * Time: 18:42
 */

namespace AV\FileHandler;

class CurlFileHandler extends FileHandlerBase
{
    //todo: support size
    public function validateFile($fileUrl, $file)
    {
        /*
        if ($this->maxSize !== null && $uploadedFile->getSize() > $this->maxSize) {
            $this->setError("File size must not be bigger than {max_size}mb", ['max_size' => $this->maxSize]);
            return false;
        }*/

        $mimeGetter = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $mimeGetter->buffer($file);

        $fileUrlNoQuery = strtok($fileUrl,'?');

        return $this->checkFileType(pathinfo($fileUrlNoQuery, PATHINFO_EXTENSION), $mimeType);
    }

    /**
     * Move a downloaded CURL file to a new destination
     *
     * @param $remoteUrl string             The remote URL where the file was downloaded from
     * @param $file mixed                   The downloaded file
     * @param $destinationPath string       The directory where the file will be saved
     * @param null $filename string         The new filename. If none is set, the original filename will be used
     * @param string $fileExistsStrategy    What to do if the file exists
     *
     * @return bool|string
     */
    public function moveFile($remoteUrl, $file, $destinationPath, $filename = null, $fileExistsStrategy = self::EXISTS_NUMBER)
    {
        if ($this->validateFile($remoteUrl, $file) === false) {
            return false;
        }

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        if ($filename === null) {
            $filename = strtok(basename($remoteUrl),'?');
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

        file_put_contents($fullPath, $file);

        return $fullPath;
    }
}
