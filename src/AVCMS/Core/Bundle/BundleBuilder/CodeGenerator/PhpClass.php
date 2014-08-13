<?php

namespace AVCMS\Core\Bundle\BundleBuilder\CodeGenerator;

class PhpClass extends \CG\Generator\PhpClass
{
    private static $phpParser;

    protected static function createMethod(\ReflectionMethod $method)
    {
        return PhpMethod::fromReflection($method, true);
    }

    public static function fromReflection(\ReflectionClass $ref, $parent = true)
    {
        $class = new static();
        $class
            ->setName($ref->name)
            ->setAbstract($ref->isAbstract())
            ->setFinal($ref->isFinal())
            ->setConstants($ref->getConstants())
        ;

        if (null === self::$phpParser) {
            if (!class_exists('Doctrine\Common\Annotations\PhpParser')) {
                self::$phpParser = false;
            } else {
                self::$phpParser = new PhpParser();
            }
        }

        if (false !== self::$phpParser) {
            $class->setUseStatements(self::$phpParser->parseClass($ref));
        }
        else {
            $source = file($ref->getFileName());
            $source = implode("", $source);
            preg_match_all("/(?<=use )(.*)(?=;)/", $source, $matches);

            foreach ($matches[0] as $namespace) {
                if (strpos($namespace, ',') !== false) {
                    $use_statements = explode(',', $namespace);
                    foreach ($use_statements as $use) {
                        if (strpos($use, ' as ') !== false) {
                            $use = explode(' as ', trim($use));
                            $class->addUseStatement($use[0], $use[1]);
                        }
                        else {
                            $class->addUseStatement(trim($use));
                        }
                    }
                }
                elseif (strpos($namespace, ' as ') !== false) {
                    $use = explode(' as ', $namespace);
                    $class->addUseStatement($use[0], $use[1]);
                }
                else {
                    $class->addUseStatement($namespace);
                }
            }
        }

        if ($docComment = $ref->getDocComment()) {
            $class->setDocblock(ReflectionUtils::getUnindentedDocComment($docComment));
        }

        foreach ($ref->getMethods() as $method) {
            if ($parent == true || !isset($ref->getParentClass()->name) || $method->getDeclaringClass()->name !== $ref->getParentClass()->name) {
                $class->setMethod(static::createMethod($method));
            }
        }

        foreach ($ref->getProperties() as $property) {
            if ($parent == true || !isset($ref->getParentClass()->name) || $property->getDeclaringClass()->name !== $ref->getParentClass()->name) {
                $class->setProperty(static::createProperty($property));
            }
        }

        if (isset($ref->getParentClass()->name)) {
            $name = $ref->getParentClass()->name;

            if (strpos($name, '\\') !== false && strpos($name, '\\') > 1) {
                $name = substr( $name, strrpos( $name, '\\' )+1 );
            }

            $class->setParentClassName($name);
        }

        return $class;
    }
}
