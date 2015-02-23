<?php
/**
 * User: Andy
 * Date: 06/11/14
 * Time: 14:10
 */

namespace AVCMS\Bundles\CmsFoundation\ConfigValidation;

use AV\Kernel\Bundle\BundleConfigValidationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class BundleConfigValidation implements BundleConfigValidationInterface
{
    public function getValidation(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->variableNode('modules')
            ->end()
            ->variableNode('module_positions')
            ->end()
            ->variableNode('admin_settings')
            ->end()
            ->variableNode('admin_settings_sections')
            ->end()
            ->variableNode('assets')
            ->end()
            ->arrayNode('menus')
                ->prototype('array')
                    ->children()
                        ->variableNode('label')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('menu_items')
                ->prototype('array')
                    ->prototype('array')
                        ->children()
                            ->variableNode('target')
                            ->end()
                            ->variableNode('icon')
                                ->isRequired()
                            ->end()
                            ->variableNode('type')
                                ->isRequired()
                            ->end()
                            ->variableNode('label')
                                ->isRequired()
                            ->end()
                            ->variableNode('parent')
                            ->end()
                            ->variableNode('permission')
                            ->end()
                            ->variableNode('translatable')
                                ->defaultValue(1)
                            ->end()
                            ->variableNode('default_order')
                                ->defaultValue(99)
                            ->end()
                            ->variableNode('settings')

                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->booleanNode('disable_content')
            ->defaultFalse()
            ->end()
            ->variableNode('permissions')
            ->end()
            ->variableNode('frontend_search')
            ->end()
        ->end();
    }
}
