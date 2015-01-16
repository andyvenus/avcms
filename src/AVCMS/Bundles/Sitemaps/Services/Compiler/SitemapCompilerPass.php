<?php
/**
 * User: Andy
 * Date: 16/01/15
 * Time: 15:38
 */

namespace AVCMS\Bundles\Sitemaps\Services\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SitemapCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('sitemaps_manager')) {
            return;
        }

        $definition = $container->getDefinition('sitemaps_manager');

        // Extensions must always be registered before everything else.
        // For instance, global variable definitions must be registered
        // afterward. If not, the globals from the extensions will never
        // be registered.
        $calls = $definition->getMethodCalls();
        $definition->setMethodCalls(array());
        foreach ($container->findTaggedServiceIds('sitemap') as $id => $attributes) {
            $definition->addMethodCall('addSitemap', array(new Reference($id)));
        }
        $definition->setMethodCalls(array_merge($definition->getMethodCalls(), $calls));
    }
}
