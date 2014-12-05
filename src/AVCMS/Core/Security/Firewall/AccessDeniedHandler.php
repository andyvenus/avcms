<?php
/**
 * User: Andy
 * Date: 04/12/14
 * Time: 13:39
 */

namespace AVCMS\Core\Security\Firewall;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    protected $twig;

    protected $defaultTemplate;

    protected $templates;

    public function __construct(\Twig_Environment $twig, $defaultTemplate, $templates)
    {
        $this->twig = $twig;
        $this->defaultTemplate = $defaultTemplate;
        $this->templates = $templates;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $class = get_class($accessDeniedException);

        if (isset($this->templates[$class])) {
            $template = $this->templates[$class];
        }
        else {
            $template = $this->defaultTemplate;
        }

        $flatten = new FlattenException();
        $error = $flatten->create($accessDeniedException, 403);

        return new Response($this->twig->render($template, ['error' => $error, 'show_error' => true]));
    }
}