<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:33
 */

namespace AV\Bundles\Model\Services;

use AV\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class DatabaseServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $queryBuilderConfig = include $configuration->config->database_config_location;

        $container->setParameter('query_builder.config', $queryBuilderConfig);

        $container->register('query_builder.factory', 'AV\Model\Connection')
            ->setArguments(array('mysql', '%query_builder.config%', 'QB', null, new Reference('dispatcher')));

        $container->setDefinition('query_builder', new Definition('AV\Model\QueryBuilder\QueryBuilderHandler'))
            ->setFactoryService('query_builder.factory')
            ->setFactoryMethod('getQueryBuilder');

        $container->register('model_factory', 'AV\Model\ModelFactory')
            ->setArguments(array(new Reference('query_builder'), new Reference('dispatcher'), new Reference('taxonomy_manager')))
            ->addMethodCall('addModelAlias', array('users', 'AVCMS\Bundles\Users\Model\Users'))
        ;

        // Validation
        $container->register('subscriber.validator.model_injector', 'AV\Model\Subscriber\InjectModelIntoValidationRuleSubscriber')
            ->setArguments(array(new Reference('model_factory')))
            ->addTag('event.subscriber')
        ;
    }
}