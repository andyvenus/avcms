<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 21:28
 */

namespace AVCMS\Bundles\Images\TwigExtension;

use AVCMS\Bundles\Images\ImagesHelper\ImagesHelper;
use AVCMS\Bundles\Images\Model\ImageCollection;
use AVCMS\Bundles\Images\Model\ImageFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ImagesTwigExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ImagesHelper
     */
    private $imagesHelper;

    public function __construct(UrlGeneratorInterface $urlGenerator, ImagesHelper $imagesHelper)
    {
        $this->urlGenerator = $urlGenerator;
        $this->imagesHelper = $imagesHelper;
    }

    public function imageThumbnailUrl($image, $size = 'md')
    {
        if ($image instanceof ImageCollection) {
            $extension = pathinfo($image->getThumbnail())['extension'];
            $id = str_replace('.' . $extension, '', $image->getThumbnail());
            $collection = $image->getId();
        }
        elseif ($image instanceof ImageFile) {
            $extension = pathinfo($image->getUrl())['extension'];
            $id = $image->getId();
            $collection = $image->getCollectionId();
        }
        else {
            throw new \Exception('Image thumbnails can only be generated for ImageCollection or ImageFile classes');
        }

        return $this->urlGenerator->generate('image_thumbnail', ['collection' => $collection, 'id' => $id, 'ext' => $extension, 'size' => $size]);
    }

    public function imageFileUrl(ImageFile $file)
    {
        return $this->imagesHelper->imageFileUrl($file);
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
