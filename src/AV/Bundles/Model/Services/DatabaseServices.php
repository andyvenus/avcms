<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:33
 */

namespace AV\Bundles\Model\Services;

use AV\Bundles\Model\Services\CompilerPass\ModelCompilerPass;
use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class DatabaseServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('query_builder.factory', 'AV\Model\Connection')
            ->setArguments(array('mysql', '%database_config_location%', null, null, new Reference('dispatcher')));

        $container->setDefinition('query_builder', new Definition('AV\Model\QueryBuilder\QueryBuilderHandler'))
            ->setFactory([new Reference('query_builder.factory'), 'getQueryBuilder'])
        ;

        $container->register('model_factory', 'AV\Bundles\Model\ModelFactory\BundleModelFactory')
            ->setArguments(array(new Reference('query_builder'), new Reference('dispatcher'), new Reference('bundle_manager')))
            ->addMethodCall('addModelAlias', array('users', 'AVCMS\Bundles\Users\Model\Users'))
        ;

        // Validation
        $container->register('subscriber.validator.model_injector', 'AV\Model\Subscriber\InjectModelIntoValidationRuleSubscriber')
            ->setArguments(array(new Reference('model_factory')))
            ->addTag('event.subscriber')
        ;

        $container->addCompilerPass(new ModelCompilerPass());
    }
}
