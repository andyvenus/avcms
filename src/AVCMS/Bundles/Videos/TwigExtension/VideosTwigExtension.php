<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 21:28
 */

namespace AVCMS\Bundles\Videos\TwigExtension;

use AVCMS\Bundles\Videos\Model\Video;
use AVCMS\Core\SettingsManager\SettingsManager;
use Twig_SimpleFilter;

class VideosTwigExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    private $videosPath;

    private $rootUrl;

    private $thumbnailsPath;

    private $settingsManager;

    public function __construct(SettingsManager $settingsManager, $rootUrl, $videosPath, $thumbnailsPath)
    {
        $this->rootUrl = $rootUrl;
        $this->videosPath = $videosPath;
        $this->thumbnailsPath = $thumbnailsPath;
        $this->settingsManager = $settingsManager;
    }

    public function videoThumbnailUrl(Video $video)
    {
        $thumbnail = $video->getThumbnail();

        if (strpos($thumbnail, '://') === false) {
            $thumbnail = $this->rootUrl.$this->thumbnailsPath.'/'.$thumbnail;
        }

        if ($this->settingsManager->getSetting('force_https')) {
            $thumbnail = str_replace('http://', 'https://', $thumbnail);
        }

        return $thumbnail;
    }

    public function embedVideo(Video $video, $showAdvert = true)
    {
        $this->setRealVideoUrl($video);

        return $this->environment->render('@Videos/embeds/'.$video->getProvider().'.twig', [
            'video' => $video,
            'show_advert' => $showAdvert
        ]);
    }

    private function setRealVideoUrl(Video $video)
    {
        $url = $video->getFile();

        if (strpos($url, '://') === false) {
            $video->setFile($this->rootUrl.$this->videosPath.'/'.$url);
        }

        if ($this->settingsManager->getSetting('force_https')) {
            $video->setFile(str_replace('http://', 'https://', $video->getFile()));
        }
    }

    public function getName()
    {
        return 'avcms_embed_video';
    }

    public function getFunctions()
    {
        return [
            'embed_video' => new \Twig_SimpleFunction(
                'embed_video',
                array($this, 'embedVideo'),
                array('is_safe' => array('html'))
            ),
            'video_thumbnail' => new \Twig_SimpleFunction(
                'video_thumbnail',
                array($this, 'videoThumbnailUrl'),
                array('is_safe' => array('html'))
            )
        ];
    }

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('duration', function ($string) {
                return $this->durationFilter($string);
            })
        ];
    }

    public function durationFilter($string)
    {
        $parts = explode(':', $string);

        foreach ($parts as $part) {
            if (is_numeric($part) && $part != '00') {
                $validParts[] = $part;
            }
        }

        if (!isset($validParts)) {
            return '';
        }

        if (count($validParts) == 1) {
            return '00:'.$validParts[0];
        }

        return implode(':', $validParts);
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
}
