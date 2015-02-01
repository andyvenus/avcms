<?php
/**
 * User: Andy
 * Date: 24/10/14
 * Time: 12:46
 */

namespace AVCMS\Core\Installer;

use AVCMS\Bundles\Installer\Model\Versions;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class Installer
{
    protected $bundleFinder;

    protected $bundleDirs;

    protected $container;

    protected $appConfig;

    protected $bundleInstallers = [];

    protected $bundleConfigs = [];

    protected $installedVersions;

    protected $failureMessage;

    protected $appDir;

    protected $defaultContentInstaller;

    public function __construct(ContainerInterface $container, InstallerBundleFinder $bundleFinder, Versions $installedVersions, $appDir = 'app')
    {
        $this->installedVersions = $installedVersions;
        $this->container = $container;
        $this->appConfig = Yaml::parse(file_get_contents($appDir.'/config/app.yml'));
        $this->bundleDirs = $bundleFinder->findBundles($this->appConfig['bundle_dirs']);
        $this->appDir = $appDir;
    }

    public function getBundlesRequiringUpdate()
    {
        $requireUpdates = [];
        foreach ($this->bundleDirs as $bundleName => $bundleDir) {
            if ($this->bundleUpToDate($bundleName) !== true) {
                $requireUpdates[$bundleName] = $bundleDir;
            }
        }

        return $requireUpdates;
    }

    public function bundleUpToDate($bundleName)
    {
        $installer = $this->getBundleInstaller($bundleName);

        $newVersions = $this->getNewVersions($this->installedVersions->getInstalledVersion($bundleName, 'bundle'), $installer->getVersions());

        return (count($newVersions) == 0 ? true : false);
	}

    /**
     * @param $bundleName
     * @return BundleInstaller
     * @throws \Exception
     */
    protected function getBundleInstaller($bundleName)
    {
        if (isset($this->bundleInstallers[$bundleName])) {
            return $this->bundleInstallers[$bundleName];
        }

        $bundleConfig = $this->getBundleConfig($bundleName);

        $installerClass = $bundleConfig['namespace'].'\\Install\\'.$bundleName.'Installer';

        return $this->bundleInstallers[$bundleName] = new $installerClass($this->container);
    }

    protected function getBundleConfig($bundleName)
    {
        if (!isset($this->bundleDirs[$bundleName])) {
            throw new \Exception('Could not find bundle directory for bundle '.$bundleName);
        }

        if (isset($this->bundleConfigs[$bundleName])) {
            return $this->bundleConfigs[$bundleName];
        }

        return $this->bundleConfigs[$bundleName] = Yaml::parse(file_get_contents($this->bundleDirs[$bundleName].'/config/bundle.yml'));
    }

    protected function getNewVersions($currentVersion, array $allVersions)
    {
        if (!$currentVersion) {
            return $allVersions;
        }

        $newVersions = [];
        foreach ($allVersions as $version => $method) {
            if ($version == $currentVersion) {
                $currentVersionFound = true;
            }
            elseif (isset($currentVersionFound)) {
                $newVersions[$version] = $method;
            }
        }

        return $newVersions;
    }

    public function updateBundle($bundleName)
    {
        $installer = $this->getBundleInstaller($bundleName);
        $versions = $this->getNewVersions($this->installedVersions->getInstalledVersion($bundleName, 'bundle'), $installer->getVersions());

        if (empty($versions) || !$versions) {
            $this->setFailureError("Bundle $bundleName is already up to date", 'info');
            return false;
        }

        foreach ($versions as $version => $method) {
            try {
                $installer->{$method}();
            }
            catch (\Exception $e) {
                $this->setFailureError($e->getMessage(), 'danger');

                return false;
            }

            try {
                $this->getDefaultContentInstaller()->handleContent($bundleName, $version);
            }
            catch (\Exception $e) {
                $this->setFailureError($e->getMessage(), 'danger');

                return false;
            }
        }

        end($versions);
        $latestVersion = key($versions);
        $this->installedVersions->setInstalledVersion($bundleName, $latestVersion, 'bundle');

        return true;
    }

    protected function setFailureError($message, $severity)
    {
        $this->failureMessage = ['message' => $message, 'severity' => $severity];
    }

    public function getFailureError()
    {
        return $this->failureMessage;
    }

    /**
     * @return DefaultContentInstaller
     */
    public function getDefaultContentInstaller()
    {
        if (!isset($this->defaultContentInstaller)) {
            include $this->appDir.'/install/'.$this->appConfig['default_content_installer'].'.php';

            $this->defaultContentInstaller = new $this->appConfig['default_content_installer']($this->container);
        }

        return $this->defaultContentInstaller;
    }
}
