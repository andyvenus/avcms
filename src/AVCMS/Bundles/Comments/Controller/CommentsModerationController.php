<?php
/**
 * User: Andy
 * Date: 14/01/15
 * Time: 21:23
 */

namespace AVCMS\Bundles\Comments\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Comments\Model\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentsModerationController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Comments\Model\Comments
     */
    private $comments;

    public function setUp()
    {
        $this->comments = $this->model('Comments');
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\JsonResponse
     * @throws AccessDeniedException
     */
    public function deleteCommentsAction(Request $request)
    {
        if (!$this->isGranted(['MODERATOR_COMMENTS_DELETE', 'ADMIN_COMMENTS'])) {
            throw new AccessDeniedException;
        }

        if (!$this->checkCsrfToken($request)) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $ids = $request->request->get('ids');

        if (!$ids) {
            return new JsonResponse(array('success' => 0, 'error' => 'No ids set'));
        }

        $ids = (array) $ids;
        foreach ($ids as $id) {
            $comment = $this->comments->getOne($id);

            if (!$comment) {
                throw new NotFoundHttpException();
            }

            $type = $comment->getContentType();

            $typeConfig = $this->container->get('comment_types_manager')->getContentType($type);

            $model = $this->model($typeConfig['model']);
            $content = $model->getOne($comment->getContentId());

            $this->deleteAllReplies($comment);

            $this->comments->delete($comment);

            if (is_callable([$content, 'getComments']) && is_callable([$content, 'setComments'])) {
                $content->setComments($this->comments->getContentCommentCount($type, $comment->getContentId()));
                $model->save($content);
            }

            if ($comment->getThread()) {
                $this->comments->updateReplies($comment->getThread());
            }
        }

        return new JsonResponse(array('success' => 1));
    }

    private function deleteAllReplies(Comment $comment)
    {
        $replies = $this->comments->getReplies($comment->getId(), $this->model('Users'));

        foreach ($replies as $reply) {
            $this->comments->delete($reply);

            $this->deleteAllReplies($reply);
        }
    }
}
