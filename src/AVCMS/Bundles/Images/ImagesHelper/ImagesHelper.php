<?php
/**
 * User: Andy
 * Date: 09/06/15
 * Time: 11:32
 */

namespace AVCMS\Bundles\Images\ImagesHelper;

use AVCMS\Bundles\Images\Model\ImageFile;

class ImagesHelper
{
    /**
     * @var string
     */
    private $siteUrl;

    /**
     * @var string
     */
    private $imagesDir;

    public function __construct($siteUrl, $imagesDir)
    {
        $this->siteUrl = $siteUrl;
        $this->imagesDir = $imagesDir;
    }

    public function imageFileUrl(ImageFile $file)
    {
        $url = $file->getUrl();

        if (strpos($url, 'http') === 0) {
            return $url;
        }
        else {
            return $this->siteUrl.$this->imagesDir.'/'.$file->getUrl();
        }
    }
}
