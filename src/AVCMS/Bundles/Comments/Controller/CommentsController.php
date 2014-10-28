<?php
/**
 * User: Andy
 * Date: 28/10/14
 * Time: 11:39
 */

namespace AVCMS\Bundles\Comments\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentsController extends Controller
{
    public function getCommentsAction(Request $request)
    {
        $contentType = $request->get('content_type');
        $contentId = $request->get('content_id');

        if (!$contentType || !$contentId) {
            throw $this->createNotFoundException();
        }

        $comments = $this->model('Comments')->getComments($contentType, $contentId, $this->model('@users'), 20, $request->get('page', 1));

        return new Response($this->render("@Comments/comments.twig", [
            'comments' => $comments,
            'content_type' => $contentType,
            'content_id' => $contentId
        ]));
    }
} 