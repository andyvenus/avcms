<?php
/**
 * User: Andy
 * Date: 24/12/2015
 * Time: 14:45
 */

namespace AVCMS\Bundles\Videos\Type;

use AVCMS\Bundles\Videos\Model\Video;

class VimeoVideo extends AbstractVideoType
{
    public function canHandleUrl($url)
    {
        return strpos($url, 'vimeo.com') !== false;
    }

    public function getId()
    {
        return 'vimeo';
    }

    public function getName()
    {
        return 'Vimeo';
    }

    public function getExampleUrl()
    {
        return 'https://vimeo.com/[video_id]';
    }

    public function getVideoAtUrl($url, Video $video)
    {
        $urlParts = explode('/', str_replace('//vimeo.com', '', $url));

        foreach ($urlParts as $urlPart) {
            if (is_numeric($urlPart)) {
                $id = $urlPart;
            }
        }

        if (!isset($id)) {
            return false;
        }

        $videoInfo = $this->parseJsonAtUrl("http://vimeo.com/api/v2/video/{$id}.json");

        if (isset($videoInfo[0]['title'])) {
            $videoInfo = $videoInfo[0];
        }

        $video->setName($videoInfo['title']);
        $video->setDescription(strip_tags($videoInfo['description']));
        $video->setDuration(gmdate("H:i:s", $videoInfo['duration']));
        $video->setThumbnail(str_replace('200x150', '480', $videoInfo['thumbnail_medium']));
        $video->setProvider($this->getId());
        $video->setProviderId($id);

        $video->setFile('https://www.vimeo.com/'.$id);

        return true;
    }
}
