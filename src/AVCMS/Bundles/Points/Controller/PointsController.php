<?php
/**
 * User: Andy
 * Date: 20/02/15
 * Time: 15:07
 */

namespace AVCMS\Bundles\Points\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class PointsController extends Controller
{
    public function getNotificationAction()
    {
        $notification = $this->get('session')->get('points_notification', []);

        if (isset($notification['message']) && $this->setting('enable_points')) {
            $notification['message'] = $this->trans($notification['message'], $notification + ['points_name' => 'points']);
            $notification['success'] = true;
        }
        else {
            $notification['success'] = false;
        }

        return new JsonResponse($notification);
    }
}
