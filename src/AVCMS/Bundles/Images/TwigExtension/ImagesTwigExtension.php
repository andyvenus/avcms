<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 21:28
 */

namespace AVCMS\Bundles\Images\TwigExtension;

use AVCMS\Bundles\Images\Model\ImageCollection;
use AVCMS\Bundles\Images\Model\ImageFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ImagesTwigExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    private $urlGenerator;

    private $siteUrl;

    private $imagesDir;

    public function __construct(UrlGeneratorInterface $urlGenerator, $siteUrl, $imagesDir)
    {
        $this->urlGenerator = $urlGenerator;
        $this->siteUrl = $siteUrl;
        $this->imagesDir = $imagesDir;
    }

    public function imageThumbnailUrl(ImageCollection $image, $size = 'md')
    {
        $extension = pathinfo($image->getThumbnail())['extension'];
        $id = str_replace('.'.$extension, '', $image->getThumbnail());

        return $this->urlGenerator->generate('image_thumbnail', ['collection' => $image->getId(), 'id' => $id, 'ext' => $extension, 'size' => $size]);
    }

    public function imageFileUrl(ImageFile $file)
    {
        $url = $file->getUrl();

        if (strpos($url, 'http') === 0) {
            return $url;
        }
        else {
            return $this->siteUrl.'/'.$this->imagesDir.'/'.$file->getUrl();
        }
    }

    public function getName()
    {
        return 'avcms_images';
    }

    public function getFunctions()
    {
        return [
            'image_file_url' => new \Twig_SimpleFunction(
                'image_file_url',
                array($this, 'imageFileUrl'),
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
