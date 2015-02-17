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
        $commentsPerLoad = 10;

        $contentType = $request->get('content_type');
        $contentId = $request->get('content_id');

        $commentTypes = $this->container->get('comment_types_manager');
        $typeConfig = $commentTypes->getContentType($contentType);

        $entity = $this->model($typeConfig['model'])->getOne($contentId);

        if ($entity === null) {
            throw $this->createNotFoundException();
        }

        if (!$request->get('thread')) {
            $comments = $this->model('Comments')->getComments($contentType, $contentId, $this->model('@users'), $commentsPerLoad, $request->get('page', 1));
        }
        else {
            $comments = $this->model('Comments')->getReplies($request->get('thread'), $this->model('Users'));
        }

        $lastPage = false;
        if (count($comments) < $commentsPerLoad || $request->get('thread')) {
            $lastPage = true;
        }

        return new Response($this->render("@Comments/comments.twig", [
            'comments' => $comments,
            'content_type' => $contentType,
            'content_id' => $contentId,
            'last_page' => $lastPage
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

        $commentTypes = $this->container->get('comment_types_manager');

        $typeConfig = $commentTypes->getContentType($contentType);

        $contentModel = $this->model($typeConfig['model']);
        $content = $contentModel->getOne($contentId);

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
        elseif ($content === null) {
            $error = 'Content Type Not Found';
        }

        if (isset($error)) {
            if (!isset($errorParams)) {
                $errorParams = [];
            }
            $commentForm->addCustomErrors([new FormError('comment', $error, true, $errorParams)]);
        }

        if (!$commentForm->isValid()) {
            return new JsonResponse(['success' => false, 'form' => $commentForm->createView()->getJsonResponseData()]);
        }

        $comment->setComment($request->get('comment'));
        $comment->setContentId($contentId);
        $comment->setContentType($contentType);
        $comment->setUserId($user->getId());
        $comment->setDate(time());
        $comment->setIp($request->getClientIp());
        $comment->setThread($request->get('thread', 0));

        $titleField = $typeConfig['title_field'];
        if (is_callable([$content, 'get'.$titleField])) {
            $comment->setContentTitle($content->{"get".$titleField}());
        }

        $comments->save($comment);
        $floodControl->setLastCommentTime($user->getId(), time());

        if ($comment->getThread()) {
            $comments->updateReplies($comment->getThread());
        }

        if (is_callable([$content, 'getComments']) && is_callable([$content, 'setComments'])) {
            $content->setComments(intval($content->getComments()) + 1);
            $contentModel->save($content);
        }

        $comment->user = $user;

        return new JsonResponse(['html' => $this->render('@Comments/comments.twig', [
            'comments' => [$comment],
            'content_type' => $contentType,
            'content_id' => $contentId,
        ]), 'success' => true]);
    }
} 
