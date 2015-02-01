<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 23:23
 */

namespace AVCMS\Bundles\Links\Install;

use AVCMS\Core\Installer\BundleInstaller;

class LinksInstaller extends BundleInstaller
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
            CREATE TABLE `{$this->prefix}links` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `anchor` varchar(80) NOT NULL DEFAULT '',
                  `url` varchar(120) NOT NULL DEFAULT '',
                  `description` text,
                  `referral_id` int(11) unsigned DEFAULT NULL,
                  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
                  `date_added` int(11) unsigned NOT NULL DEFAULT '0',
                  `date_edited` int(11) unsigned NOT NULL DEFAULT '0',
                  `admin_seen` int(11) NOT NULL DEFAULT '1',
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}
