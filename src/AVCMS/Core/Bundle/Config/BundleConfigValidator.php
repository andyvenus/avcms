<?php
/**
 * User: Andy
 * Date: 14/08/2014
 * Time: 13:58
 */

namespace AVCMS\Core\Bundle\Config;

use AV\Kernel\Bundle\Config\BundleConfigValidator as BaseBundleConfigValidator;

class BundleConfigValidator extends BaseBundleConfigValidator
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = parent::getConfigTreeBuilder();
        $rootNode = $treeBuilder->root('bundle_config');

        $rootNode ->children()
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
            ->variableNode('permissions')
            ->end()
        ->end();

        return $treeBuilder;
    }
}