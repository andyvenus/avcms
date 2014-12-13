<?php
/**
 * User: Andy
 * Date: 13/12/14
 * Time: 18:42
 */

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Core\Controller\Controller;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WallpapersImageController extends Controller
{
    public function imageAction(Request $request)
    {
        $width = $request->get('w', 800);
        $height = $request->get('h', 600);
        $anchor = $request->get('a', 'bottom-left');

        $man = new ImageManager(['driver' => 'GD']);

        $img = $man->make($this->container->getParameter('root_dir').'/web/wp.jpg');

        $reqRatio = $width / $height;
        $origRatio = $img->width() / $img->height();

        if ($reqRatio > $origRatio) {
            $img->widen($width);
        } else {
            $img->heighten($height);
        }

        $img->resizeCanvas($width, $height, $anchor);

        return new Response($img->encode(), 200, ['Content-Type' => 'image/png']);
    }
}
