<?php
/**
 * User: Andy
 * Date: 01/03/15
 * Time: 16:21
 */

namespace AVCMS\Bundles\FacebookConnect\Install;

use AVCMS\Core\Installer\BundleInstaller;

class FacebookConnectInstaller extends BundleInstaller
{
    public function getVersions()
    {
        return [];
    }


    public function bundleCleanup()
    {
        if (!$this->columnExists('users', 'facebook__id')) {
            $this->PDO->exec("ALTER TABLE `{$this->prefix}users` ADD `facebook__id` bigint(11) unsigned DEFAULT NULL");
        }
    }
}
