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
        $width = $request->get('width');
        $height = $request->get('height');

        if ($width === null || $height === null) {
            $resolution = $request->get('resolution');
            list($width, $height) = explode('x', $resolution);
        }

        if (!$width || !$height) {
            throw $this->createNotFoundException('No valid resolution');
        }

        $wallpapers = $this->model('Wallpapers');
        $wallpaper = $wallpapers->findOne($request->get('id'))->first();
        if (!$wallpaper) {
            throw $this->createNotFoundException();
        }

        $cacheDir = $this->container->getParameter('web_path').'/wallpapers/'.$wallpaper->getId();
        if ($thumbnail === true) {
            $cacheDir .= '/thumbnail';
        }
        $filename = $width.'x'.$height.'.'.pathinfo($wallpaper->getFile(), PATHINFO_EXTENSION);
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

        $imageManager = new ImageManager(['driver' => 'GD']);

        try {
            $img = $imageManager->make($this->container->getParameter('root_dir') . '/' . $this->bundle->config->wallpapers_dir . '/' . $wallpaper->getFile());
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
