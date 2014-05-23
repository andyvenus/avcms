<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Bundles\Blog\Finder\BlogPostsFinder;
use AVCMS\Bundles\Blog\Form\BlogPostsFilterForm;
use AVCMS\Bundles\Blog\Form\PostForm;
use AVCMS\Core\Controller\AdminController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogAdminController extends AdminController
{
    public function blogHomeAction(Request $request)
    {
       if ($request->get('ajax_depth') == 'editor') {
           return new Response('');
       }

        return new Response($this->renderAdminSection(
                '@admin/blog_browser.twig',
                $request->get('ajax_depth'),
                array())
        );
    }

    public function editPostAction(Request $request)
    {
        $model = $this->model('Posts');

        $current_username = $this->activeUser()->getUser()->getUsername();
        $form_blueprint = new PostForm($current_username);

        return $this->editItem($request, $model, $form_blueprint, '@AVBlog/edit_post.twig', '@admin/blog_browser.twig', 'blog_edit_post');
    }

    public function finderAction(Request $request)
    {
        $posts_model = $this->model('Posts');

        $finder = new BlogPostsFinder($posts_model);
        $finder->setSearchFields(array('title'));
        $finder->setResultsPerPage(10);
        $finder->handleRequest($request, array('page' => 1, 'order' => 'newest', 'search' => null));

        $users_model = $this->model('@users');

        $posts = $finder->getQuery()
            ->modelJoin($users_model, array('username'))
            ->get();

        return new Response($this->render('@admin/finder.twig', array('posts' => $posts)));
    }

    protected function getIndexTemplateVars($ajax_depth)
    {
        $template_vars = parent::getIndexTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new BlogPostsFilterForm())->createView();

        return $template_vars;
    }
}