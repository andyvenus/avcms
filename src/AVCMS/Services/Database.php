<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:33
 */

namespace AVCMS\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class Database implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->setParameter('query_builder.config', array(
            'driver'    => 'mysql', // Db driver
            'host'      => 'localhost',
            'database'  => 'avcms',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8', // Optional
            'collation' => 'utf8_unicode_ci', // Optional
            'prefix'    => 'avms_', // Table prefix, optional
        ));

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