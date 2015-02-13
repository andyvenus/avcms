<?php
/**
 * User: Andy
 * Date: 19/10/14
 * Time: 19:22
 */

namespace AV\Kernel\Bundle\Config;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class BundleConfigValidator implements ConfigurationInterface
{
    protected $bundleDirs;

    protected $treeBuilder;

    public function getConfigTreeBuilder()
    {
        if (isset($this->treeBuilder)) {
            return $this->treeBuilder;
        }

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bundle_config');

        $this->getDefaultConfigValidation($rootNode);

        $this->getExtendedConfigValidation($rootNode);

        return $this->treeBuilder = $treeBuilder;
    }

    public function setBundleDirs(array $dirs)
    {
        $this->bundleDirs = $dirs;
    }

    protected function getExtendedConfigValidation(ArrayNodeDefinition $rootNode)
    {
        if (!isset($this->bundleDirs)) {
            return;
        }

        foreach ($this->bundleDirs as $bundlesNameSpace => $bundlesDir) {
            $dirItr = new \DirectoryIterator($bundlesDir);
            foreach ($dirItr as $dir) {
                if ($dir->isDot()) {
                    continue;
                }

                $namespace = $bundlesNameSpace . '\\' . $dir->getBasename();

                $class = $namespace.'\ConfigValidation\BundleConfigValidation';

                if (class_exists($class)) {
                    $validation = new $class();
                    $validation->getValidation($rootNode);
                }
            }
        }
    }

    protected function getDefaultConfigValidation(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->ignoreExtraKeys()
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('name')
            ->isRequired()
            ->end()
            ->variableNode('namespace')
            ->isRequired()
            ->end()
            ->variableNode('require')
            ->end()
            ->variableNode('model')
            ->end()
            ->variableNode('enabled')
            ->end()
            ->variableNode('services')
            ->end()
            ->booleanNode('core')
            ->defaultFalse()
            ->end()
            ->variableNode('required_bundles')
            ->end()
            ->variableNode('parent_bundle')
            ->end()
            ->variableNode('parent_config')
            ->end()
            ->variableNode('directory')
            ->end()
            ->variableNode('route')
            ->end()
            ->variableNode('config')
            ->end()
            ->variableNode('container_params')
            ->end()
        ->end();
    }
}
