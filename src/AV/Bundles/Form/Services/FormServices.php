<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:35
 */

namespace AV\Bundles\Form\Services;

use AV\Bundles\Form\Services\Compiler\FormBuilderTranslatorPass;
use AV\Bundles\Form\Services\Compiler\FormTransformerPass;
use AV\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FormServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('form.builder', 'AV\Bundles\Form\Form\FormBuilder')
            ->setArguments(array(new Reference('form.handler_factory')))
            ->addMethodCall('setContainer', array(new Reference('service_container')))
            ->addMethodCall('setEventDispatcher', array(new Reference('dispatcher')))
        ;

        $container->register('form.handler_factory', 'AV\Form\FormHandlerFactory')
            ->setArguments(array(new Reference('form.request_handler'), new Reference('form.entity_processor'), new Reference('form.transformer_manager'), new Reference('dispatcher')))
        ;

        $container->register('form.request_handler', 'AV\Form\RequestHandler\SymfonyRequestHandler');

        $container->register('form.entity_processor', 'AV\Model\EntityProcessor');

        $container->register('csrf.token', 'AVCMS\Core\Security\Csrf\CsrfToken');

        $container->register('subscriber.csrf.form_plugin', 'AVCMS\Core\Security\Csrf\Events\CsrfFormPlugin')
            ->setArguments(array(new Reference('csrf.token')))
            ->addTag('event.subscriber')
        ;

        $container->register('form.transformer_manager', 'AV\Form\Transformer\TransformerManager');

        $container->register('form.unix_timestamp_transformer', 'AV\Form\Transformer\UnixTimestampTransformer')
            ->addTag('form.transformer')
        ;

        // Twig Extension

        $container->register('twig.form.extension', 'AV\Form\Twig\FormExtension')
            ->addTag('twig.extension')
        ;

        $container->addCompilerPass(new FormBuilderTranslatorPass());
        $container->addCompilerPass(new FormTransformerPass());
    }
}