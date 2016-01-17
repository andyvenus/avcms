<?php
/**
 * User: Andy
 * Date: 17/01/2016
 * Time: 10:43
 */

namespace AVCMS\Bundles\Videos\Type;

use AVCMS\Bundles\Videos\Model\Video;

class VineVideo extends AbstractVideoType
{
    public function canHandleUrl($url)
    {
        return strpos($url, 'vine.co/v') !== false;
    }

    public function getId()
    {
        return 'vine';
    }

    public function getName()
    {
        return 'Vine';
    }

    public function getExampleUrl()
    {
        return 'https://vine.co/v/[video_id]';
    }

    public function getVideoAtUrl($url, Video $video)
    {
        $this->parseCommonHtmlAtUrl($url, $video);

        $video->setDuration('00:06');

        $video->setProvider($this->getId());
        $video->setProviderId($this->getIdFromUrl($url), 'last');
    }
}
