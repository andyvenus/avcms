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
        return $this->handleManage($request, '@Comments/admin/comments_browser.twig');
    }

    public function commentsFinderAction(Request $request)
    {
        $comments = $this->comments->find()
            ->join($this->model('@users'), ['id', 'username', 'slug'])
            ->setResultsPerPage(10)
            ->setSearchFields(['content_title', 'comment', 'ip'])
            ->handleRequest($request, ['page' => 1, 'contentType' => 'all', 'order' => 'newest', 'search' => null, 'id' => null])
            ->get();

        foreach ($comments as $comment) {
            $type = $comment->getContentType();
            if ($this->commentTypes->contentTypeValid($type) === false) {
                continue;
            }

            $typeConfig = $this->commentTypes->getContentType($type);

            $contentModel = $this->model($typeConfig['model']);
            $content = $contentModel->getOne($comment->getContentId());

            if (!$content) {
                continue;
            }

            $params = $typeConfig['frontend_route_params'];
            $frontendRoute = $typeConfig['frontend_route'];

            $contentInfo = [];

            $titleField = $typeConfig['title_field'];

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

        return new Response($this->renderAdminSection('@Comments/admin/comments_finder.twig', ['comments' => $comments]));
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $contentTypes = $this->commentTypes->getContentTypes();

        $contentTypesSelect['all'] = 'All';
        foreach ($contentTypes as $contentTypeName => $contentType) {
            $contentTypesSelect[$contentTypeName] = $contentType['name'];
        }

        $templateVars['finder_filters_form'] = $this->buildForm(new CommentFiltersForm($contentTypesSelect))->createView();

        return $templateVars;
    }
} 
