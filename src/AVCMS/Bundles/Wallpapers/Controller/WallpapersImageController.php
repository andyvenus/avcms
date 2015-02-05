<?php
/**
 * User: Andy
 * Date: 13/12/14
 * Time: 18:42
 */

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Core\Controller\Controller;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WallpapersImageController extends Controller
{
    public function imageAction(Request $request, $thumbnail = false, $download = false)
    {
        $resolutionsManager = $this->container->get('wallpaper.resolutions_manager');

        $width = $request->get('width');
        $height = $request->get('height');

        $thumbnailSize = 'md';

        if ($width === null || $height === null && $thumbnail === true && $request->get('thumbnail_size')) {
            $resolution = $resolutionsManager->getThumbnailResolution($request->get('thumbnail_size'));
            if ($resolution !== null) {
                $thumbnailSize = $request->get('thumbnail_size');
                list($width, $height) = explode('x', $resolution);
            }
        }
        elseif ($resolutionsManager->checkValidResolution($width, $height) === false) {
            $width = $height = null;
        }

        if (!$width || !$height) {
            throw $this->createNotFoundException();
        }

        $wallpapers = $this->model('Wallpapers');
        $wallpaper = $wallpapers->findOne($request->get('slug'))->first();
        if (!$wallpaper) {
            throw $this->createNotFoundException();
        }

        $cacheDir = $this->getParam('web_path').'/wallpapers/'.$wallpaper->getSlug();
        if ($thumbnail === true) {
            $cacheDir .= '/thumbnail';
            $filename = $thumbnailSize.'.'.pathinfo($wallpaper->getFile(), PATHINFO_EXTENSION);
        }
        else {
            $filename = $width.'x'.$height.'.'.pathinfo($wallpaper->getFile(), PATHINFO_EXTENSION);
        }

        $imagePath = $cacheDir.'/'.$filename;

        $headers = [];
        if ($download === true) {
            $headers['Content-Disposition'] = 'attachment; filename="'.$wallpaper->getSlug().'-'.$filename.'"';
            $this->container->get('hitcounter')->registerHit($wallpapers, $wallpaper->getId(), 'total_downloads');
        }

        if (file_exists($imagePath)) {
            $mimeGetter = new \finfo(FILEINFO_MIME_TYPE);
            $file = file_get_contents($imagePath);
            $headers['Content-Type'] = $mimeGetter->buffer($file);
            return new Response($file, 200, $headers);
        }

        $imageManager = new ImageManager(['driver' => $this->setting('wallpaper_image_manipulation_library')]);

        try {
            $img = $imageManager->make($this->getParam('root_dir') . '/' . $this->bundle->config->wallpapers_dir . '/' . $wallpaper->getFile());
        }
        catch (NotReadableException $e) {
            throw $this->createNotFoundException('Wallpaper Source Image Not Found');
        }

        if ($thumbnail === true || $wallpaper->getResizeType() === 'crop') {
            $reqRatio = $width / $height;
            $origRatio = $img->width() / $img->height();

            if ($reqRatio > $origRatio) {
                $img->widen($width);
            } else {
                $img->heighten($height);
            }

            if (!$cropPos = $wallpaper->getCropPosition()) {
                $cropPos = 'center';
            }

            $img->resizeCanvas($width, $height, $cropPos);
        }
        else {
            $img->resize($width, $height);
        }

        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }
        $img->save($imagePath);

        $headers['Content-Type'] = $img->mime;
        return new Response($img->encode(), 200, $headers);
    }
}
