<?php
/**
 * User: Andy
 * Date: 25/10/14
 * Time: 15:40
 */

namespace AVCMS\Bundles\Tags\Install;

use AVCMS\Core\Installer\BundleInstaller;

class TagsInstaller extends BundleInstaller
{
    public function getVersions()
    {
        return [
            '1.0' => 'install_1_0_0'
        ];
    }

    public function install_1_0_0()
    {
        $this->sql("
            CREATE TABLE `{$this->prefix}tags` (
                  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(40) NOT NULL DEFAULT '',
                  `seo_url` varchar(40) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `seo_url` (`seo_url`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}tag_taxonomy` (
                  `content_id` int(5) NOT NULL,
                  `taxonomy_id` int(5) NOT NULL,
                  `content_type` varchar(10) NOT NULL DEFAULT '',
                  KEY `game_id` (`content_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}