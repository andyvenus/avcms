<?php
/**
 * User: Andy
 * Date: 24/01/15
 * Time: 11:40
 */

namespace AV\Bundles\Model\Services\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ModelCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('model_factory')) {
            return;
        }

        foreach ($container->findTaggedServiceIds('model') as $id => $attributes) {
            $modelDefinition = $container->getDefinition($id);

            $modelDefinition->setFactory([new Reference('model_factory'), 'create']);
            $modelDefinition->setArguments([$modelDefinition->getClass()]);
        }
    }
}
