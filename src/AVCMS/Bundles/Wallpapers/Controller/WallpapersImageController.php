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
    public function imageAction(Request $request)
    {
        $width = $request->get('width', 800);
        $height = $request->get('height', 600);

        $wallpaper = $this->model('Wallpapers')->getOne($request->get('id'));
        if (!$wallpaper) {
            throw $this->createNotFoundException();
        }

        $man = new ImageManager(['driver' => 'GD']);

        try {
            $img = $man->make($this->container->getParameter('root_dir') . '/' . $this->bundle->config->wallpapers_dir . '/' . $wallpaper->getFile());
        }
        catch (NotReadableException $e) {
            exit('source image not found');
        }

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


        $thumbDir = $this->container->getParameter('web_path').'/wallpapers/'.$wallpaper->getId().'/thumbnail';
        if (!file_exists($thumbDir)) {
            mkdir($thumbDir, 0777, true);
        }
        $img->save($thumbDir.'/'.$width.'x'.$height.'.'.$img->extension);

        return new Response($img->encode(), 200, ['Content-Type' => $img->mime]);
    }
}
