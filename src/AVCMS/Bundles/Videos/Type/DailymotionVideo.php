<?php
/**
 * User: Andy
 * Date: 03/01/2016
 * Time: 11:41
 */

namespace AVCMS\Bundles\Videos\Type;

use AVCMS\Bundles\Videos\Model\Video;

class DailymotionVideo extends AbstractVideoType
{
    public function canHandleUrl($url)
    {
        return strpos($url, 'dailymotion.com/video') > -1;
    }

    public function getId()
    {
        return 'dailymotion';
    }

    public function getName()
    {
        return 'Dailymotion';
    }

    public function getExampleUrl()
    {
        return 'http://www.dailymotion.com/video/[id]_[video_slug]';
    }

    public function getIdFromUrl($url)
    {
        return substr($url, strrpos($url, '/') + 1, 7);
    }

    public function getVideoAtUrl($url, Video $video)
    {
        $id = $this->getIdFromUrl($url);

        if (!$id) {
            return false;
        }

        $data = $this->parseJsonAtUrl('https://api.dailymotion.com/video/'.$id.'?fields=title,duration_formatted,description,thumbnail_480_url');

        $video->setFile($url);
        $video->setThumbnail($data['thumbnail_480_url']);

        $video->setName($data['title']);
        $video->setDescription($data['description']);
        $video->setDuration($data['duration_formatted']);

        $video->setProviderId($id);
        $video->setProvider($this->getId());
    }
}
