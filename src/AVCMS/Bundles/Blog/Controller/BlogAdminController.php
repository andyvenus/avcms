<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Blog\Form\BlogPostsFilterForm;
use AVCMS\Bundles\Blog\Form\BlogPostAdminForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BlogAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Blog\Model\BlogPosts
     */
    protected $blogPosts;

    protected $browserTemplate = '@Blog/admin/blog_browser.twig';

    public function setUp()
    {
        $this->blogPosts = $this->model('BlogPosts');

        if (!$this->isGranted('ADMIN_BLOG')) {
            throw new AccessDeniedException;
        }
    }

    public function blogHomeAction(Request $request)
    {
        return $this->handleManage($request, '@Blog/admin/blog_browser.twig');
    }

    public function editPostAction(Request $request)
    {
        $form_blueprint = new BlogPostAdminForm($request->get('id', 0), $this->activeUser()->getId());

        return $this->handleEdit($request, $this->blogPosts, $form_blueprint, 'blog_edit_post', '@Blog/admin/edit_post.twig', '@Blog/admin/blog_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $usersModel = $this->model('Users');

        $finder = $this->blogPosts->find()
            ->setSearchFields(array('title'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'search' => null, 'tags' => null, 'id' => null))
            ->join($usersModel, array('username'));

        $posts = $finder->get();

        return new Response($this->render('@Blog/admin/blog_finder.twig', array('items' => $posts, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->blogPosts);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->blogPosts);
    }

    protected function getSharedTemplateVars($ajaxDepth)
    {
        $templateVars = parent::getSharedTemplateVars($ajaxDepth);

        $templateVars['finder_filters_form'] = $this->buildForm(new BlogPostsFilterForm())->createView();

        return $templateVars;
    }
}
