<?php
/**
 * User: Andy
 * Date: 05/05/15
 * Time: 15:38
 */

namespace AVCMS\Bundles\Points\Controller;

use AVCMS\Core\Controller\Controller;

class PointsModulesController extends Controller
{
    public function topPointsModule($adminSettings)
    {
        $players = $this->model('Users')->query()->orderBy('points__points', 'DESC')->limit($adminSettings['limit'])->get();

        return $this->render('@Points/module/top_points_module.twig', ['players' => $players], true);
    }
}
