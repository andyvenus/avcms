<?php
/**
 * User: Andy
 * Date: 20/02/15
 * Time: 15:07
 */

namespace AVCMS\Bundles\Points\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PointsController extends Controller
{
    public function getNotificationAction()
    {
        $notification = $this->get('session')->get('points_notification', []);

        if (isset($notification['message']) && $this->setting('enable_points')) {
            $notification['message'] = $this->trans($notification['message'], $notification + ['points_name' => $this->setting('points_name')]);
            $notification['success'] = true;
        }
        else {
            $notification['success'] = false;
        }

        return new JsonResponse($notification);
    }

    public function addPointsAction(Request $request)
    {
        $type = $request->get('type');

        if ($type == 'game_points') {
            $message = 'You earned {points} {points_name} for playing a game';
        }

        if (isset($message)) {
            $this->get('points_manager')->addPoints($type, $message);
        }

        return $this->getNotificationAction();
    }
}
