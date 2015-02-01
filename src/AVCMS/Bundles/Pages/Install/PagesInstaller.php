<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 23:27
 */

namespace AVCMS\Bundles\Pages\Install;

use AVCMS\Core\Installer\BundleInstaller;

class PagesInstaller extends BundleInstaller
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
            CREATE TABLE `{$this->prefix}pages` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `title` varchar(120) DEFAULT NULL,
                  `content` longtext,
                  `date_added` int(11) unsigned DEFAULT NULL,
                  `date_edited` int(11) unsigned DEFAULT NULL,
                  `published` tinyint(1) unsigned DEFAULT NULL,
                  `publish_date` int(11) unsigned DEFAULT NULL,
                  `creator_id` int(11) unsigned DEFAULT NULL,
                  `editor_id` int(11) unsigned DEFAULT NULL,
                  `slug` varchar(120) DEFAULT NULL,
                  `hits` int(11) NOT NULL DEFAULT '0',
                  `show_title` int(11) DEFAULT '1',
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}
