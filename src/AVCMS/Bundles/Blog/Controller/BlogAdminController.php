<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Bundles\Blog\Form\BlogPostsFilterForm;
use AVCMS\Bundles\Blog\Form\PostForm;
use AVCMS\Bundles\Admin\Controller\AdminController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogAdminController extends AdminController
{
    public function blogHomeAction(Request $request)
    {
       return $this->manage($request, '@Blog/blog_browser.twig');
    }

    public function editPostAction(Request $request)
    {
        $model = $this->model('Posts');

        $form_blueprint = new PostForm($request->get('id', 0), $this->activeUser()->getUser()->getId());

        return $this->edit($request, $model, $form_blueprint, 'blog_edit_post', '@Blog/edit_post.twig', '@Blog/blog_browser.twig', array('content_name' => 'Post'));
    }

    public function finderAction(Request $request)
    {
        $posts_model = $this->model('Posts');

        $users_model = $this->model('@users');

        $finder = $posts_model->find()
            ->setSearchFields(array('title'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'search' => null, 'tags' => null, 'id' => null))
            ->join($users_model, array('username'));

        $posts = $finder->get();

        return new Response($this->render('@Blog/blog_finder.twig', array('items' => $posts, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        $posts_model = $this->model('Posts');

        return $this->delete($request, $posts_model);
    }

    public function togglePublishedAction(Request $request)
    {
        $posts_model = $this->model('Posts');

        return $this->togglePublished($request, $posts_model);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new BlogPostsFilterForm())->createView();

        return $template_vars;
    }
}