<?php
/**
 * User: Andy
 * Date: 18/03/15
 * Time: 14:18
 */

namespace AVCMS\Bundles\Friends\Install;

use AVCMS\Core\Installer\BundleInstaller;

class FriendsInstaller extends BundleInstaller
{
    public function getVersions()
    {
        return array(
            '1.0' => 'install_1_0_0'
        );
    }

    public function install_1_0_0()
    {
        $this->sql("
            CREATE TABLE `{$this->prefix}friend_requests` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `sender_id` int(11) DEFAULT NULL,
                  `receiver_id` int(11) DEFAULT NULL,
                  `date` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}friends` (
                  `user1` int(11) DEFAULT NULL,
                  `user2` int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}
