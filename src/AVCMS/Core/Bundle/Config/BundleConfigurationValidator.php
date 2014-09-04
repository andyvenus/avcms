<?php
/**
 * User: Andy
 * Date: 14/08/2014
 * Time: 13:58
 */

namespace AVCMS\Core\Bundle\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class BundleConfigurationValidator implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $tree_builder = new TreeBuilder();
        $root_node = $tree_builder->root('model');

        $root_node
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
                ->variableNode('route')
                ->end()
                ->variableNode('template')
                ->end()
                ->variableNode('enabled')
                ->end()
                ->variableNode('services')
                ->end()
                ->booleanNode('core')
                    ->defaultFalse()
                ->end()
                ->booleanNode('disable_content')
                    ->defaultFalse()
                ->end()
                ->variableNode('required_bundles')
                ->end()
                ->variableNode('parent_bundle')
                ->end()
                ->variableNode('user_settings')
                ->end()
                ->variableNode('assets')
                ->end()
                ->variableNode('menu_items')
                ->end()
                ->variableNode('directory')
                ->end()
                ->variableNode('user_settings_sections')
                ->end()
            ->end();

        return $tree_builder;
    }
}