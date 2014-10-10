<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:33
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AVCMS\Core\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class DatabaseServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $queryBuilderConfig = include 'app/config/database.php';

        $container->setParameter('query_builder.config', $queryBuilderConfig);

        $container->register('query_builder.factory', 'AVCMS\Core\Database\Connection')
            ->setArguments(array('mysql', '%query_builder.config%', 'QB', null, new Reference('dispatcher')));

        $container->setDefinition('query_builder', new Definition('AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler'))
            ->setFactoryService('query_builder.factory')
            ->setFactoryMethod('getQueryBuilder');

        $container->register('model_factory', 'AVCMS\Core\Model\ModelFactory')
            ->setArguments(array(new Reference('query_builder'), new Reference('dispatcher'), new Reference('taxonomy_manager')))
            ->addMethodCall('addModelAlias', array('users', 'AVCMS\Bundles\Users\Model\Users'))
        ;
    }
} 