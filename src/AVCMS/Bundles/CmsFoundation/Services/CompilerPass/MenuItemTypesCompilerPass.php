<?php
/**
 * User: Andy
 * Date: 05/01/15
 * Time: 15:08
 */

namespace AVCMS\Bundles\CmsFoundation\Services\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds tagged menu.item_type services to menu manager service
 */
class MenuItemTypesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('menu_manager')) {
            return;
        }

        $definition = $container->getDefinition('menu_manager');

        $calls = $definition->getMethodCalls();
        $definition->setMethodCalls(array());
        foreach ($container->findTaggedServiceIds('menu.item_type') as $id => $attributes) {
            $definition->addMethodCall('addMenuItemType', array(new Reference($id), $attributes[0]['id']));
        }
        $definition->setMethodCalls(array_merge($definition->getMethodCalls(), $calls));
    }
}
