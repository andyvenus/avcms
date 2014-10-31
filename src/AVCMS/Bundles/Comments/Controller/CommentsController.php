<?php
/**
 * User: Andy
 * Date: 28/10/14
 * Time: 11:39
 */

namespace AVCMS\Bundles\Comments\Controller;

use AV\Form\FormError;
use AVCMS\Bundles\Comments\Form\CommentForm;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentsController extends Controller
{
    public function getCommentsAction(Request $request)
    {
        $contentType = $request->get('content_type');
        $contentId = $request->get('content_id');

        $entity = $this->getEntity($contentType, $contentId);

        if ($entity == null) {
            throw $this->createNotFoundException();
        }

        $comments = $this->model('Comments')->getComments($contentType, $contentId, $this->model('@users'), 20, $request->get('page', 1));

        return new Response($this->render("@Comments/comments.twig", [
            'comments' => $comments,
            'content_type' => $contentType,
            'content_id' => $contentId
        ]));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function addCommentAction(Request $request)
    {
        $contentType = $request->get('content_type');
        $contentId = $request->get('content_id');
        $content = $this->getEntity($contentType, $contentId);

        $commentTypes = $this->container->get('comment_types_manager');

        $entity = $this->getEntity($contentType, $contentId);

        $user = $this->activeUser();

        $floodControl = $this->model('CommentFloodControl');
        $lastCommentTime = $floodControl->getLastCommentTime($user->getId());
        $floodControlTime = time() - $user->group->getFloodControlTime();

        $comments = $this->model('Comments');
        $comment = $comments->newEntity();

        $commentForm = $this->buildForm(new CommentForm(), $request, $comment);

        if (!$this->isGranted('PERM_ADD_COMMENT')) {
            $error = 'Permission Denied';
        }
        elseif($floodControlTime < $lastCommentTime) {
            $error = "Please wait at least {seconds} seconds between making comments";
            $errorParams = ['seconds' => $user->group->getFloodControlTime()];
        }
        elseif ($entity == null) {
            $error = 'Content Type Not Found';
        }

        if (isset($error)) {
            if (!isset($errorParams)) {
                $errorParams = [];
            }
            $commentForm->addCustomErrors([new FormError('comment', $error, true, $errorParams)]);

            return new JsonResponse(['success' => false, 'form' => $commentForm->createView()->getJsonResponseData()]);
        }

        $comment->setComment($request->get('comment'));
        $comment->setContentId($contentId);
        $comment->setContentType($contentType);
        $comment->setUserId($user->getId());
        $comment->setDate(time());

        $titleField = $commentTypes->getTitleField($contentType);
        if (is_callable([$content, 'get'.$titleField])) {
            $comment->setContentTitle($content->{"get".$titleField}());
        }

        $comments->save($comment);
        $floodControl->setLastCommentTime($user->getId(), time());

        $comment->user = $user;

        return new JsonResponse(['html' => $this->render('@Comments/comments.twig', ['comments' => [$comment]]), 'success' => true]);
    }

    protected function getEntity($contentType, $contentId)
    {
        $typesManager = $this->container->get('comment_types_manager');

        $entity = null;
        if ($typesManager->contentTypeValid($contentType) === true) {
            $entity = $this->model($typesManager->getModelClass($contentType))->getOne($contentId);
        }

        return $entity;
    }
} 