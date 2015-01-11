<?php
/**
 * User: Andy
 * Date: 28/10/14
 * Time: 11:17
 */

namespace AVCMS\Bundles\Comments\Twig;

use AV\Form\FormHandler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommentsTwigExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    protected $urlGenerator;

    public function __construct(FormHandler $form, UrlGeneratorInterface $urlGenerator)
    {
        $this->form = $form;
        $this->urlGenerator = $urlGenerator;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function renderCommentsArea($contentType, $content, $totalComments, $template = '@Comments/comments_area.twig')
    {
        if (is_object($content)) {
            if (!is_callable([$content, 'getId'])) {
                throw new \Exception('The content passed to the twig comment() function must be an ID or an object with a getId method');
            }
            $content = $content->getId();
        }

        $this->form->setAction($this->urlGenerator->generate('add_comment', ['content_id' => $content, 'content_type' => $contentType], UrlGeneratorInterface::ABSOLUTE_URL));

        return $this->environment->render($template, [
            'content_type' => $contentType,
            'content_id' => $content,
            'form' => $this->form->createView(),
            'total_comments' => $totalComments
        ]);
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
