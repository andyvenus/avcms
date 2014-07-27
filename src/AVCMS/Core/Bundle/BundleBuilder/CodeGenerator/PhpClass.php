<?php

namespace AVCMS\Core\Bundle\BundleBuilder\CodeGenerator;

class PhpClass extends \CG\Generator\PhpClass
{
    protected static function createMethod(\ReflectionMethod $method)
    {
        return PhpMethod::fromReflection($method, true);
    }
}
