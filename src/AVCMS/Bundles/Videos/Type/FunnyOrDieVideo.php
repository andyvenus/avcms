<?php
/**
 * User: Andy
 * Date: 17/01/2016
 * Time: 10:43
 */

namespace AVCMS\Bundles\Videos\Type;

use AVCMS\Bundles\Videos\Model\Video;

class FunnyOrDieVideo extends AbstractVideoType
{
    public function canHandleUrl($url)
    {
        return strpos($url, 'funnyordie.com/videos') !== false;
    }

    public function getId()
    {
        return 'funnyordie';
    }

    public function getName()
    {
        return 'Funny Or Die';
    }

    public function getExampleUrl()
    {
        return 'http://www.funnyordie.com/videos/[video_id]/video-name-here';
    }

    public function getIdFromUrl($url)
    {
        return $this->extractIdFromUrl(str_replace('://', '', $url), 2);
    }

    public function getVideoAtUrl($url, Video $video)
    {
        $this->parseCommonHtmlAtUrl($url, $video);

        $video->setProvider($this->getId());
        $video->setProviderId($this->getIdFromUrl($url));
    }
}
