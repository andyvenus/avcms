<?php
/**
 * User: Andy
 * Date: 15/05/15
 * Time: 10:05
 */

namespace AVCMS\Bundles\Images\Controller;

use AVCMS\Core\Controller\Controller;
use Curl\Curl;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageThumbnailsController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Images\Model\ImageFiles
     */
    private $imageFiles;

    public function setUp()
    {
        $this->imageFiles = $this->model('ImageFiles');
    }

    public function thumbnailAction(Request $request)
    {
        $file = $this->imageFiles->getImageFile($request->get('collection'), $request->get('id'));

        if (!$file) {
            throw $this->createNotFoundException();
        }

        $size = $request->get('size');
        if (!in_array($size, ['sm', 'md', 'lg'])) {
            throw $this->createNotFoundException();
        }

        if (strpos($file->getUrl(), 'http') === 0) {
            $image = $file->getUrl();

            if(!ini_get('allow_url_fopen')) {
                $curl = new Curl();
                $curl->setOpt(CURLOPT_FOLLOWLOCATION, true);
                $image = $curl->get($image);

                if (!$image) {
                    throw $this->createNotFoundException('Could not get remote image for thumbnail');
                }
            }
        }
        else {
            $image = $this->getParam('root_dir').'/'.$this->getParam('images_dir').'/'.$file->getUrl();
        }

        $imageManager = new ImageManager(['driver' => 'GD']);

        $thumbnail = $imageManager->make($image);

        $thumbnailPath = $this->getParam('root_dir').'/'.$this->getParam('image_thumbnails_dir').'/'.$file->getImageId();

        if (!file_exists($thumbnailPath)) {
            mkdir($thumbnailPath, 0777, true);
        }

        $filename = $file->getId().'-'.$size.'.'.pathinfo($file->getUrl(), PATHINFO_EXTENSION);

        if ($size == 'sm') {
            $dimensions = [100, 100];
        }
        elseif ($size == 'lg') {
            $dimensions = [400, 400];
        }
        else {
            $dimensions = [200, 200];
        }

        $settings = $this->get('settings_manager');

        $thumbnail->resize($dimensions[0], $dimensions[1], function($c) use ($settings) {
            if ($settings->getSetting('images_thumbnail_crop') == 'maintain_ratio') {
                $c->aspectRatio();
            }
            $c->upsize();
        });

        $thumbnail->save($thumbnailPath.'/'.$filename);

        $headers['Content-Type'] = $thumbnail->mime;
        return new Response($thumbnail->encode(), 200, $headers);
    }
}
