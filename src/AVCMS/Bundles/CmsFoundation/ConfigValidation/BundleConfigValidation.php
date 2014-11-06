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
            ->variableNode('user_settings')
            ->end()
            ->variableNode('user_settings_sections')
            ->end()
            ->variableNode('assets')
            ->end()
            ->variableNode('menu_items')
            ->end()
            ->booleanNode('disable_content')
            ->defaultFalse()
            ->end()
            ->variableNode('permissions')
            ->end()
        ->end();
    }
}