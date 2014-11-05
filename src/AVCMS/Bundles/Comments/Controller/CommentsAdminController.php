<?php
/**
 * User: Andy
 * Date: 30/10/14
 * Time: 13:43
 */

namespace AVCMS\Bundles\Comments\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Comments\Form\CommentFiltersForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Comments\Model\Comments
     */
    private $comments;

    /**
     * @var \AVCMS\Core\Comments\CommentTypesManager
     */
    private $commentTypes;

    public function setUp()
    {
        if (!$this->isGranted('ADMIN_COMMENTS')) {
            throw new AccessDeniedException;
        }

        $this->comments = $this->model('Comments');
        $this->commentTypes = $this->container->get('comment_types_manager');
    }

    public function manageCommentsAction(Request $request)
    {
        return $this->handleManage($request, '@Comments/comments_browser.twig');
    }

    public function commentsFinderAction(Request $request)
    {
        $comments = $this->comments->find()
            ->join($this->model('@users'), ['username', 'slug'])
            ->setResultsPerPage(10)
            ->setSearchFields(['content_title', 'comment'])
            ->handleRequest($request, ['page' => 1, 'contentType' => 'all', 'order' => 'newest', 'search' => null, 'id' => null])
            ->get();

        foreach ($comments as $comment) {
            $type = $comment->getContentType();
            if ($this->commentTypes->contentTypeValid($type) === false) {
                continue;
            }

            $contentModel = $this->model($this->commentTypes->getModelClass($comment->getContentType()));
            $content = $contentModel->getOne($comment->getContentId());

            if (!$content) {
                continue;
            }

            $params = $this->commentTypes->getFrontendRouteParams($type);
            $frontendRoute = $this->commentTypes->getFrontendRoute($type);

            $contentInfo = [];

            $titleField = $this->commentTypes->getTitleField($type);

            $contentInfo['title'] = '?';

            if (is_callable([$content, "get$titleField"])) {
                $contentInfo['title'] = $content->{"get$titleField"}();
            }

            if (!$frontendRoute) {
                $contentInfo['url'] = null;
            }
            elseif (empty($params)) {
                $contentInfo['url'] = $this->generateUrl($frontendRoute, UrlGeneratorInterface::ABSOLUTE_URL);
            }
            else {
                foreach ($params as $paramName) {
                    if (!is_callable([$content, "get$paramName"])) {
                        unset($urlParams);
                        continue;
                    }
                    $urlParams[$paramName] = $content->{"get$paramName"}();
                }

                if (isset($urlParams)) {
                    $contentInfo['url'] = $this->generateUrl($frontendRoute, $urlParams, UrlGeneratorInterface::ABSOLUTE_URL);
                }
                else {
                    $contentInfo['url'] = null;
                }
            }

            $comment->content = $contentInfo;

        }

        return new Response($this->renderAdminSection('@Comments/comments_finder.twig', $request->get('ajax_depth'), ['comments' => $comments]));
    }

    public function deleteCommentsAction(Request $request)
    {
        return $this->handleDelete($request, $this->comments);
    }

    protected function getSharedTemplateVars($ajaxDepth)
    {
        $templateVars = parent::getSharedTemplateVars($ajaxDepth);

        $contentTypes = $this->commentTypes->getContentTypes();

        $contentTypesSelect['all'] = 'All';
        foreach ($contentTypes as $contentTypeName => $contentType) {
            $contentTypesSelect[$contentTypeName] = $contentType['name'];
        }

        $templateVars['finder_filters_form'] = $this->buildForm(new CommentFiltersForm($contentTypesSelect))->createView();

        return $templateVars;
    }
} 