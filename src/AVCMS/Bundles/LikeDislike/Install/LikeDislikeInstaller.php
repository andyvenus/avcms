<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 23:29
 */

namespace AVCMS\Bundles\LikeDislike\Install;

use AVCMS\Core\Installer\BundleInstaller;

class LikeDislikeInstaller extends BundleInstaller
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
            CREATE TABLE `{$this->prefix}ratings` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `content_id` int(10) unsigned NOT NULL,
                  `content_type` varchar(30) NOT NULL DEFAULT '',
                  `user_id` int(10) unsigned NOT NULL,
                  `date` int(11) unsigned NOT NULL DEFAULT '0',
                  `rating` int(11) NOT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}
