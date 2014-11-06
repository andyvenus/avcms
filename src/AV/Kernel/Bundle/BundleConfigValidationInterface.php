<?php
/**
 * User: Andy
 * Date: 06/11/14
 * Time: 14:09
 */

namespace AV\Kernel\Bundle;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

interface BundleConfigValidationInterface
{
    public function getValidation(ArrayNodeDefinition $rootNode);
} 