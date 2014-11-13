<?php
/**
 * User: Andy
 * Date: 13/11/14
 * Time: 11:22
 */

namespace AVCMS\Bundles\Reports\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ReportsModuleController extends Controller
{
    public function reportsAdminModule()
    {
        $totalReports = $this->model('Reports')->query()->count();

        return new Response($this->render('@Reports/reports_module.twig', ['total_reports' => $totalReports]));
    }
} 