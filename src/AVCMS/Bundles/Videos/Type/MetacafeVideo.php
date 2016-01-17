<?php
/**
 * User: Andy
 * Date: 17/01/2016
 * Time: 10:43
 */

namespace AVCMS\Bundles\Videos\Type;

use AVCMS\Bundles\Videos\Model\Video;

class MetacafeVideo extends AbstractVideoType
{
    public function canHandleUrl($url)
    {
        return strpos($url, 'metacafe.com/watch') !== false;
    }

    public function getId()
    {
        return 'metacafe';
    }

    public function getName()
    {
        return 'Metacafe';
    }

    public function getExampleUrl()
    {
        return 'http://www.metacafe.com/watch/[video_id]/video_title_goes_here/';
    }

    public function getVideoAtUrl($url, Video $video)
    {
        $this->parseCommonHtmlAtUrl($url, $video);

        $video->setProvider($this->getId());
        $video->setProviderId($this->getIdFromUrl($url));
    }
}
