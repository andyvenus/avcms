<?php
/**
 * User: Andy
 * Date: 09/10/2014
 * Time: 23:55
 */

namespace AVCMS\Bundles\Admin\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDashboardController extends AdminBaseController
{
    public function dashboardAction(Request $request)
    {
        return new Response($this->renderAdminSection('@Admin/dashboard.twig'));
    }
} 
