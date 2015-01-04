<?php
/**
 * User: Andy
 * Date: 06/11/14
 * Time: 13:35
 */

namespace AVCMS\Bundles\LikeDislike\ConfigValidation;

use AV\Kernel\Bundle\BundleConfigValidationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class BundleConfigValidation implements BundleConfigValidationInterface
{
    public function getValidation(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('ratings')
                    ->prototype('array')
                    ->children()
                        ->variableNode('name')
                        ->isRequired()
                        ->end()
                        ->variableNode('model')
                        ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
} 
