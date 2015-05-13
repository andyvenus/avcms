<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 21:28
 */

namespace AVCMS\Bundles\Images\TwigExtension;

use AVCMS\Bundles\Images\Model\Image;

class ImagesTwigExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    private $imagesPath;

    private $rootUrl;

    private $thumbnailsPath;

    public function __construct($rootUrl, $imagesPath, $thumbnailsPath)
    {
        $this->rootUrl = $rootUrl;
        $this->imagesPath = $imagesPath;
        $this->thumbnailsPath = $thumbnailsPath;
    }

    public function imageThumbnailUrl(Image $image)
    {
        $thumbnail = $image->getThumbnail();

        if (strpos($thumbnail, '://') === false) {
            return $this->rootUrl.$this->thumbnailsPath.'/'.$thumbnail;
        }

        return $thumbnail;
    }

    public function embedImage(Image $image)
    {
        return $this->environment->render('@Images/player.twig');
    }

    public function getName()
    {
        return 'avcms_embed_image';
    }

    public function getFunctions()
    {
        return [
            'embed_image' => new \Twig_SimpleFunction(
                'embed_image',
                array($this, 'embedImage'),
                array('is_safe' => array('html'))
            ),
            'image_thumbnail' => new \Twig_SimpleFunction(
                'image_thumbnail',
                array($this, 'imageThumbnailUrl'),
                array('is_safe' => array('html'))
            )
        ];
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
}
