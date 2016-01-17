<?php
/**
 * User: Andy
 * Date: 17/01/2016
 * Time: 10:43
 */

namespace AVCMS\Bundles\Videos\Type;

use AVCMS\Bundles\Videos\Model\Video;

class VevoVideo extends AbstractVideoType
{
    public function canHandleUrl($url)
    {
        return strpos($url, 'vevo.com/watch') !== false;
    }

    public function getId()
    {
        return 'vevo';
    }

    public function getName()
    {
        return 'Vevo';
    }

    public function getExampleUrl()
    {
        return 'http://www.vevo.com/watch/[video_id]';
    }

    public function getVideoAtUrl($url, Video $video)
    {
        $this->parseCommonHtmlAtUrl($url, $video);

        $video->setProvider($this->getId());
        $video->setProviderId($this->getIdFromUrl($url, 'last'));
    }
}
