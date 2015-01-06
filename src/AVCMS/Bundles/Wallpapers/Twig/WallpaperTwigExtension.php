<?php
/**
 * User: Andy
 * Date: 27/12/14
 * Time: 13:28
 */

namespace AVCMS\Bundles\Wallpapers\Twig;

use AVCMS\Bundles\Wallpapers\Model\Wallpaper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WallpaperTwigExtension extends \Twig_Extension
{
    protected $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getName()
    {
        return 'avcms_wallpaper';
    }

    public function getFunctions()
    {
        return array(
            'wp_thumbnail_url' => new \Twig_SimpleFunction('wp_thumbnail_url', array($this, 'thumbnailUrl'), array('is_safe' => array('html'))),
            'wp_image_url' => new \Twig_SimpleFunction('wp_image_url', array($this, 'imageUrl'), array('is_safe' => array('html'))),
            'wp_download_url' => new \Twig_SimpleFunction('wp_download_url', array($this, 'downloadUrl'), array('is_safe' => array('html'))),
        );
    }

    public function thumbnailUrl(Wallpaper $wallpaper, $width = null, $height = null)
    {
        if (!$width) {
            $width = 500;
        }
        if (!$height) {
            $height = 281;
        }

        $extension = pathinfo($wallpaper->getFile())['extension'];

        return $this->urlGenerator->generate('wallpaper_thumbnail', ['id' => $wallpaper->getId(), 'width' => $width, 'height' => $height, 'ext' => $extension]);
    }

    public function imageUrl(Wallpaper $wallpaper, $width, $height, $route = 'wallpaper_image')
    {
        $extension = pathinfo($wallpaper->getFile())['extension'];

        return $this->urlGenerator->generate($route, ['id' => $wallpaper->getId(), 'width' => $width, 'height' => $height, 'ext' => $extension]);
    }

    public function downloadUrl(Wallpaper $wallpaper, $width, $height)
    {
        return $this->imageUrl($wallpaper, $width, $height, 'wallpaper_download');
    }
}
