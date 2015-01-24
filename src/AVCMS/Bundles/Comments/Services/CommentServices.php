<?php
/**
 * User: Andy
 * Date: 28/10/14
 * Time: 11:25
 */

namespace AVCMS\Bundles\Comments\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CommentServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('comment_types_manager', 'AVCMS\Core\Comments\CommentTypesManager')
            ->setArguments([new Reference('bundle_manager')])
        ;

        $container->register('comment_form', 'AVCMS\Bundles\Comments\Form\CommentForm');

        $container->register('comment_form_handler', 'AV\Form\FormHandler')
            ->setArguments([new Reference('comment_form')])
            ->setFactory([new Reference('form.builder'), 'buildForm'])
        ;

        $container->register('twig.comments_extension', 'AVCMS\Bundles\Comments\Twig\CommentsTwigExtension')
            ->setArguments([new Reference('comment_form_handler'), new Reference('router'), new Reference('dispatcher')])
            ->addTag('twig.extension')
        ;
    }
}
