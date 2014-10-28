<?php
/**
 * User: Andy
 * Date: 28/10/14
 * Time: 11:17
 */

namespace AVCMS\Bundles\Comments\Twig;

use AV\Model\Model;

class CommentsTwigExtension extends \Twig_Extension
{
    protected $model;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function renderCommentsArea($contentType, $content, $template = '@Comments/comments_area.twig')
    {
        if (is_object($content)) {
            if (!is_callable([$content, 'getId'])) {
                throw new \Exception('The content passed to the twig comment() function must be an ID or an object with a getId method');
            }
            $content = $content->getId();
        }

        return $this->environment->render($template, ['content_type' => $contentType, 'content_id' => $content]);
    }

    public function getFunctions()
    {
        return array(
            'comments' => new \Twig_SimpleFunction('comments',
                array($this, 'renderCommentsArea'),
                array('is_safe' => array('html')
                )
            )
        );
    }

    public function getName()
    {
        return 'avcms_comments';
    }
}