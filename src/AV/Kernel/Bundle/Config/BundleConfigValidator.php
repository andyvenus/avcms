<?php
/**
 * User: Andy
 * Date: 19/10/14
 * Time: 19:22
 */

namespace AV\Kernel\Bundle\Config;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class BundleConfigValidator implements ConfigurationInterface
{
    public function getConfigTreeBuilder(TreeBuilder $treeBuilder = null, $rootNode = null)
    {
        if ($rootNode == null) {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('bundle_config');
        }

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('name')
            ->isRequired()
            ->end()
            ->variableNode('namespace')
                ->isRequired()
            ->end()
            ->variableNode('require')
            ->end()
            ->variableNode('model')
            ->end()
            ->variableNode('enabled')
            ->end()
            ->variableNode('services')
            ->end()
            ->booleanNode('core')
                ->defaultFalse()
            ->end()
            ->variableNode('required_bundles')
            ->end()
            ->variableNode('parent_bundle')
            ->end()
            ->variableNode('directory')
            ->end()
            ->variableNode('route')
            ->end()
            ->variableNode('config')
            ->end()
            ->variableNode('container_params')
            ->end()
        ->end();

        return $treeBuilder;
    }
}