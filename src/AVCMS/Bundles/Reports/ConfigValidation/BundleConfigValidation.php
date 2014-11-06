<?php
/**
 * User: Andy
 * Date: 06/11/14
 * Time: 14:24
 */

namespace AVCMS\Bundles\Reports\ConfigValidation;

use AV\Kernel\Bundle\BundleConfigValidationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class BundleConfigValidation implements BundleConfigValidationInterface
{
    public function getValidation(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->arrayNode('reports')
                ->prototype('array')
                    ->children()
                        ->variableNode('name')
                            ->isRequired()
                        ->end()
                        ->variableNode('model')
                            ->isRequired()
                        ->end()
                        ->variableNode('title_field')
                            ->defaultNull()
                        ->end()
                        ->variableNode('route')
                            ->defaultNull()
                        ->end()
                        ->variableNode('route_params')
                            ->defaultValue([])
                        ->end()
                        ->variableNode('user_id_field')
                            ->defaultNull()
                        ->end()
                        ->variableNode('content_field')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}