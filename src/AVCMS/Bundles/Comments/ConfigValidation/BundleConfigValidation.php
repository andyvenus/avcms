<?php
/**
 * User: Andy
 * Date: 06/11/14
 * Time: 13:35
 */

namespace AVCMS\Bundles\Comments\ConfigValidation;

use AV\Kernel\Bundle\BundleConfigValidationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class BundleConfigValidation implements BundleConfigValidationInterface
{
    public function getValidation(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
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
            ->end();
    }
} 