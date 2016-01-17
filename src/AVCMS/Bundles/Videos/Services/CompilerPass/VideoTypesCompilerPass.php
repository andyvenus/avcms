<?php
/**
 * User: Andy
 * Date: 05/01/15
 * Time: 15:08
 */

namespace AVCMS\Bundles\Videos\Services\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds tagged menu.item_type services to menu manager service
 */
class VideoTypesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('video_manager')) {
            return;
        }

        $definition = $container->getDefinition('video_manager');

        $calls = $definition->getMethodCalls();
        $definition->setMethodCalls(array());

        foreach ($container->findTaggedServiceIds('video_type') as $id => $attributes) {
            $definition->addMethodCall('addType', array(new Reference($id)));
        }

        $definition->setMethodCalls(array_merge($definition->getMethodCalls(), $calls));
    }
}
