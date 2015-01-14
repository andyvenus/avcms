<?php
/**
 * User: Andy
 * Date: 14/01/15
 * Time: 21:23
 */

namespace AVCMS\Bundles\Comments\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
            $type = $comment->getContentType();

            $typeConfig = $this->container->get('comment_types_manager')->getContentType($type);

            $model = $this->model($typeConfig['model']);
            $content = $model->getOne($comment->getContentId());

            if (is_callable([$content, 'getComments']) && is_callable([$content, 'setComments'])) {
                $content->setComments(intval($content->getComments()) - 1);
                $model->save($content);
            }
        }

        $this->comments->deleteById($ids);

        return new JsonResponse(array('success' => 1));
    }
}
