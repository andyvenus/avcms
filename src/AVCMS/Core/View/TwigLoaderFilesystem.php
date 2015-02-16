<?php
/**
 * User: Andy
 * Date: 17/12/2013
 * Time: 21:25
 */

namespace AVCMS\Core\View;


use AV\Kernel\Bundle\ResourceLocator;
use AVCMS\Core\SettingsManager\SettingsManager;
use AVCMS\Core\View\Exception\TemplateConfigException;
use Twig_Error_Loader;

class TwigLoaderFilesystem extends \Twig_Loader_Filesystem
{
    protected $templateInvalid = false;

    protected $resourceLocator;

    protected $settingsManager;

    public function __construct(ResourceLocator $resourceLocator, SettingsManager $settingsManager, $rootDir)
    {
        $this->resourceLocator = $resourceLocator;
        $this->settingsManager = $settingsManager;

        $templateDir = $rootDir.'/'.$settingsManager->getSetting('template');
        $emailTemplateDir = $rootDir.'/'.$settingsManager->getSetting('email_template');

        if (!is_dir($templateDir)) {
            $this->templateInvalid = true;
            return;
        }

        $this->setPaths([$rootDir.'/webmaster/resources/templates/'.basename($templateDir), $templateDir]);
        $this->setPaths([$emailTemplateDir], 'email');
    }

    protected function findTemplate($name)
    {
        if ($name === 'index.twig' && $this->templateInvalid === true) {
            throw new TemplateConfigException(sprintf('Template %s doesn\'t exist', $this->settingsManager->getSetting('template')));
        }

        $name = $this->normalizeName($name);

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $this->validateName($name);

        list($namespace, $shortname) = $this->parseName($name);

        $originalOnly = false;
        if ($namespace[0] === '@') {
            $originalOnly = true;
            $namespace = str_replace('@', '', $namespace);
        }

        if ($this->resourceLocator->bundleExists($namespace)) {
            return $this->cache[$name] = $this->resourceLocator->findFileDirectory($namespace, $shortname, 'templates', $originalOnly);
        }

        if (!isset($this->paths[$namespace])) {
            throw new Twig_Error_Loader(sprintf('There are no registered paths for namespace "%s".', $namespace));
        }

        foreach ($this->paths[$namespace] as $path) {
            if (is_file($path.'/'.$shortname)) {
                return $this->cache[$name] = $path.'/'.$shortname;
            }
        }

        throw new Twig_Error_Loader(sprintf('Unable to find template "%s" (looked into: %s).', $name, implode(', ', $this->paths[$namespace])));
    }
} 
