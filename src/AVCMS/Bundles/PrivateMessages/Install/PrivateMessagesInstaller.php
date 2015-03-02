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
        return [
            '1.0' => 'install_1_0_0'
        ];
    }

    public function getHooks()
    {
        return [
            'Users' => ['1.0' => 'alterUserTable']
        ];
    }

    public function install_1_0_0()
    {
        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}messages` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `subject` varchar(80) DEFAULT NULL,
                  `body` text,
                  `recipient_id` int(11) unsigned DEFAULT NULL,
                  `sender_id` int(11) unsigned DEFAULT NULL,
                  `date` int(11) unsigned DEFAULT NULL,
                  `read` tinyint(1) NOT NULL DEFAULT '0',
                  `ip` varchar(15) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `read` (`read`),
                  KEY `recipient_id` (`recipient_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public function alterUserTable()
    {
        $this->PDO->exec("ALTER TABLE `{$this->prefix}users` ADD `messages__total_unread` int(11) NOT NULL DEFAULT '0'");
    }
}
