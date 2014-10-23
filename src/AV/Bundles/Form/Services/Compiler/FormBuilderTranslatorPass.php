<?php
/**
 * User: Andy
 * Date: 23/10/14
 * Time: 11:38
 */

namespace AV\Bundles\Form\Services\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FormBuilderTranslatorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('form.builder') === false) {
            return;
        }

        $definition = $container->getDefinition('form.builder');

        if ($container->hasDefinition('translator') === true) {
            $definition->addMethodCall('setTranslator', [new Reference('translator')]);
        }
    }
}