<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:01
 */

namespace AVCMS\Bundles\Blog\Controller;

use AVCMS\Bundles\Blog\Form\BlogPostsFilterForm;
use AVCMS\Bundles\Blog\Form\PostForm;
use AVCMS\Core\Controller\AdminController;
use AVCMS\Core\Finder\Finder;
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
        //$this->requirePermissions(array('manage_blog', 'edit_blog_post'));

        $posts = $this->model('Posts');

        $post = $posts->getOneOrNew($request->get('id', 0));

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $current_username = $this->activeUser()->getUser()->getUsername();
        $form = $this->buildForm(new PostForm($current_username), $post, $request);

        if ($form->isSubmitted()) {
            $id = null;
            if ($form->isValid()) {
                $id = $posts->save($post);
            }

            return new JsonResponse(array(
                'form' => $form->createView()->getJsonResponseData(),
                'redirect' => $this->generateUrl('blog_edit_post', array('id' => $id))
            ));
        }

        return new Response($this->renderAdminSection(
            '@AVBlog/edit_post.twig',
            $request->get('ajax_depth'),
            array('item' => $post, 'form' => $form->createView(), 'browser_template' => '@admin/blog_browser.twig'))
        );
    }

    public function finderAction(Request $request)
    {
        $posts_model = $this->model('Posts');

        $finder = new Finder($posts_model);
        $finder->setResultsPerPage(10);
        $finder->handleRequest($request, array('page' => 1, 'order' => 'newest'));

        $users_model = $this->model($this->config['users_model']);

        $posts = $finder->getQuery()
            ->modelJoin($users_model, array('username'))
            ->get();

        return new Response($this->render('@admin/finder.twig', array('posts' => $posts)));
    }

    public function turnipsAction()
    {
        return new Response('<div id="ajax_page_title">lol</div>Ha');
    }

    protected function getIndexTemplateVars($ajax_depth)
    {
        $template_vars = parent::getIndexTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new BlogPostsFilterForm())->createView();

        return $template_vars;
    }
}