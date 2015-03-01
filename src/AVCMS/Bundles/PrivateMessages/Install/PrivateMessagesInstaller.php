<?php
/**
 * User: Andy
 * Date: 01/03/15
 * Time: 16:21
 */

namespace AVCMS\Bundles\PrivateMessages\Install;

use AVCMS\Core\Installer\BundleInstaller;

class PrivateMessagesInstaller extends BundleInstaller
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
        $this->PDO->exec("ALTER TABLE `{$this->prefix}users` ADD `messages__total_unread` int(11) NOT NULL DEFAULT '0'");
    }
}
