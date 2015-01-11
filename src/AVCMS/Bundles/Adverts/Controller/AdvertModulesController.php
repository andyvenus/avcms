<?php
/**
 * User: Andy
 * Date: 11/01/15
 * Time: 13:51
 */

namespace AVCMS\Bundles\Adverts\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertModulesController extends Controller
{
    public function advertModule($userSettings)
    {
        $advert = $this->model('Adverts')->getOne($userSettings['advert_id']);

        if (!$advert) {
            return new Response('Ad not found');
        }

        return new Response($advert->getContent());
    }
}
