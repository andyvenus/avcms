<?php
/**
 * User: Andy
 * Date: 23/10/14
 * Time: 11:43
 */

namespace AV\Bundles\Form\Services\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FormTransformerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('form.transformer_manager')) {
            return;
        }

        $definition = $container->getDefinition('form.transformer_manager');

        foreach ($container->findTaggedServiceIds('form.transformer') as $id => $attributes) {
            $definition->addMethodCall('registerTransformer', array(new Reference($id)));
        }
    }
} 