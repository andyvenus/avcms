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

    public function getHooks()
    {
        return [
            'Users' => ['1.0' => 'alterUserTable']
        ];
    }

    public function alterUserTable()
    {
        $this->PDO->exec("ALTER TABLE `{$this->prefix}users` ADD `facebook__id` bigint(11) unsigned DEFAULT NULL");
    }
}
