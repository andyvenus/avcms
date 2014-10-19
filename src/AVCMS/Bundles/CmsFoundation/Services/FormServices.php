<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:35
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AVCMS\Core\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FormServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('form.builder', 'AVCMS\Bundles\CmsFoundation\Form\Factory\FormBuilder')
            ->setArguments(array(new Reference('form.handler_factory'), new Reference('translator'), new Reference('dispatcher')))
            ->addMethodCall('setContainer', array(new Reference('service_container')))
        ;

        $container->register('form.handler_factory', 'AV\Form\FormHandlerFactory')
            ->setArguments(array(new Reference('form.request_handler'), new Reference('form.entity_processor'), new Reference('form.transformer_manager'), new Reference('dispatcher')))
        ;

        $container->register('form.request_handler', 'AV\Form\RequestHandler\SymfonyRequestHandler');

        $container->register('form.entity_processor', 'AV\Model\EntityProcessor');

        $container->register('csrf.token', 'AVCMS\Core\Security\Csrf\CsrfToken');

        $container->register('listener.csrf.form_plugin', 'AVCMS\Core\Security\Csrf\Events\CsrfFormPlugin')
            ->setArguments(array(new Reference('csrf.token')))
            ->addTag('event.subscriber')
        ;

        $container->register('form.transformer_manager', 'AV\Form\Transformer\TransformerManager')
            ->addMethodCall('registerTransformer', array(new Reference('form.unix_timestamp_transformer')))
        ;

        $container->register('form.unix_timestamp_transformer', 'AV\Form\Transformer\UnixTimestampTransformer');

        // Validation

        $container->register('listener.validator.model_injector', 'AVCMS\Core\Validation\Events\RuleModelFactoryInjector')
            ->setArguments(array(new Reference('model_factory')))
            ->addTag('event.subscriber')
        ;
    }
}