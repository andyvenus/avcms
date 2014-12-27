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
        );
    }

    public function thumbnailUrl(Wallpaper $wallpaper, $width, $height)
    {
        return $this->urlGenerator->generate('wallpaper_thumbnail', ['id' => $wallpaper->getId(), 'width' => $width, 'height' => $height]);
    }
}
