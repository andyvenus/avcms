<?php
/**
 * User: Andy
 * Date: 20/12/14
 * Time: 12:54
 */

namespace AVCMS\Bundles\Categories\ConfigValidation;

use AV\Kernel\Bundle\BundleConfigValidationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class BundleConfigValidation implements BundleConfigValidationInterface
{
    public function getValidation(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
            ->variableNode('categories')
            ->end();
    }
}
