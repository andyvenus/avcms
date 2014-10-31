<?php
/**
 * User: Andy
 * Date: 14/08/2014
 * Time: 13:58
 */

namespace AVCMS\Core\Bundle\Config;

use AV\Kernel\Bundle\Config\BundleConfigValidator as BaseBundleConfigValidator;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class BundleConfigValidator extends BaseBundleConfigValidator
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bundle_config');

        $rootNode->children()
            ->variableNode('modules')
            ->end()
            ->variableNode('module_positions')
            ->end()
                ->variableNode('template')
                ->end()
                ->booleanNode('disable_content')
                    ->defaultFalse()
                ->end()
                ->variableNode('user_settings')
                ->end()
                ->variableNode('assets')
                ->end()
                ->variableNode('menu_items')
                ->end()
            ->variableNode('user_settings_sections')
            ->end()
            ->arrayNode('comments')
                ->prototype('array')
                    ->children()
                        ->variableNode('name')
                            ->isRequired()
                        ->end()
                        ->variableNode('model')
                            ->isRequired()
                        ->end()
                        ->variableNode('title_field')
                            ->isRequired()
                        ->end()
                        ->variableNode('frontend_route')
                            ->isRequired()
                        ->end()
                        ->variableNode('frontend_route_params')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->variableNode('permissions')
            ->end();

        return parent::getConfigTreeBuilder($treeBuilder, $rootNode);
    }
}