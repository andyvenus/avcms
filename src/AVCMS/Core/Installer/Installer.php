<?php
/**
 * User: Andy
 * Date: 24/10/14
 * Time: 12:46
 */

namespace AVCMS\Core\Installer;

class Installer
{
    public function __construct()
    {

    }

    public function bundleUpToDate($bundleName)
    {
        $installer = $this->getBundleInstaller($bundleName);

        $newVersions = $this->getNewVersions($this->getInstalledVersion($bundleName), $installer->getVersions());

        return (count($newVersions) == 0 ? true : false);
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

    protected function updateBundle($bundleName)
    {
        $installer = $this->getBundleInstaller($bundleName);
        $versions = $this->getNewVersions($this->getInstalledVersion($bundleName), $installer->getVersions());

        foreach ($versions as $version => $method) {
            try {
                $installer->{$method}();
            }
            catch (\Exception $e) {
                $this->setFailureMessage($e->getMessage());

                return false;
            }
        }

        return true;
    }

    protected function getBundleInstaller($bundleName)
    {

    }
}