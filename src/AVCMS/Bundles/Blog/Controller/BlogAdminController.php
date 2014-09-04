<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Bundles\Blog\Form\BlogPostsFilterForm;
use AVCMS\Bundles\Blog\Form\PostForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Blog\Model\Posts
     */
    protected $blogPosts;

    protected $browserTemplate = '@Blog/blog_browser.twig';

    public function setUp()
    {
        $this->blogPosts = $this->model('Posts');
    }

    public function blogHomeAction(Request $request)
    {
       return $this->createManageResponse($request, '@Blog/blog_browser.twig');
    }

    public function editPostAction(Request $request)
    {
        $form = $this->buildForm(new PostForm($request->get('id', 0), $this->activeUser()->getUser()->getId()));

        $helper = $this->editContentHelper($this->blogPosts, $form);

        $helper->handleRequestAndSave($request);

        if (!$helper->contentExists()) {
            throw $this->createNotFoundException();
        }

        return $this->createEditResponse($helper, $request, '@Blog/edit_post.twig', $this->browserTemplate, array('blog_edit_post', array('id' => $helper->getEntity()->getId())));
    }

    public function finderAction(Request $request)
    {
        $usersModel = $this->model($this->bundle->model->users);

        $finder = $this->blogPosts->find()
            ->setSearchFields(array('title'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'search' => null, 'tags' => null, 'id' => null))
            ->join($usersModel, array('username'));

        $posts = $finder->get();

        return new Response($this->render('@Blog/blog_finder.twig', array('items' => $posts, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->delete($request, $this->blogPosts);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->togglePublished($request, $this->blogPosts);
    }

    protected function getSharedTemplateVars($ajaxDepth)
    {
        $templateVars = parent::getSharedTemplateVars($ajaxDepth);

        $templateVars['finder_filters_form'] = $this->buildForm(new BlogPostsFilterForm())->createView();

        return $templateVars;
    }
}