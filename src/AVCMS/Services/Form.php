<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:35
 */

namespace AVCMS\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class Form implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('csrf.token', 'AVCMS\Core\Security\Csrf\CsrfToken');

        $container->register('listener.csrf.form_plugin', 'AVCMS\Core\Security\Csrf\Events\CsrfFormPlugin')
            ->setArguments(array(new Reference('csrf.token')))
            ->addTag('event.subscriber')
        ;

        $container->register('form.transformer_manager', 'AVCMS\Core\Form\Transformer\TransformerManager')
            ->addMethodCall('registerTransformer', array(new Reference('form.unix_timestamp_transformer')))
        ;

        $container->register('form.unix_timestamp_transformer', 'AVCMS\Core\Form\Transformer\UnixTimestampTransformer');

        // Validation

        $container->register('listener.validator.model_injector', 'AVCMS\Core\Validation\Events\RuleModelFactoryInjector')
            ->setArguments(array(new Reference('model_factory')))
            ->addTag('event.subscriber')
        ;
    }
}