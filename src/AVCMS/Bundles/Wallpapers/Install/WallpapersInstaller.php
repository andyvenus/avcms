<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 23:36
 */

namespace AVCMS\Bundles\Wallpapers\Install;

use AVCMS\Core\Installer\BundleInstaller;

class WallpapersInstaller extends BundleInstaller
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
            CREATE TABLE `{$this->prefix}wallpapers` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) DEFAULT NULL,
                  `description` text,
                  `file` text,
                  `category_id` int(11) unsigned DEFAULT NULL,
                  `category_parent_id` int(11) unsigned DEFAULT NULL,
                  `date_added` int(11) unsigned DEFAULT NULL,
                  `date_edited` int(11) unsigned DEFAULT NULL,
                  `creator_id` int(11) unsigned DEFAULT NULL,
                  `editor_id` int(11) unsigned DEFAULT NULL,
                  `published` tinyint(1) DEFAULT NULL,
                  `publish_date` int(11) unsigned DEFAULT NULL,
                  `featured` int(1) NOT NULL DEFAULT '0',
                  `slug` varchar(255) DEFAULT NULL,
                  `hits` int(11) unsigned NOT NULL DEFAULT '0',
                  `last_hit` int(10) unsigned NOT NULL DEFAULT '0',
                  `resize_type` varchar(20) DEFAULT NULL,
                  `crop_position` varchar(30) DEFAULT NULL,
                  `total_downloads` int(11) unsigned NOT NULL DEFAULT '0',
                  `last_download` int(11) unsigned NOT NULL,
                  `comments` int(11) unsigned NOT NULL DEFAULT '0',
                  `original_width` int(10) unsigned NOT NULL DEFAULT '0',
                  `original_height` int(10) unsigned NOT NULL DEFAULT '0',
                  `likes` int(10) unsigned NOT NULL DEFAULT '0',
                  `dislikes` int(11) unsigned NOT NULL DEFAULT '0',
                  `submitter_id` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `slug` (`slug`),
                  KEY `published` (`published`),
                  KEY `publish_date` (`publish_date`),
                  KEY `category_id` (`category_id`),
                  KEY `category_parent_id` (`category_parent_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}wallpaper_categories` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(120) DEFAULT NULL,
                  `order` int(11) DEFAULT NULL,
                  `parent` int(11) DEFAULT NULL,
                  `slug` varchar(120) DEFAULT NULL,
                  `description` text,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}wallpaper_submissions` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) DEFAULT NULL,
                  `description` varchar(255) DEFAULT NULL,
                  `file` varchar(255) DEFAULT NULL,
                  `category_id` int(11) unsigned DEFAULT NULL,
                  `date_added` int(11) unsigned DEFAULT NULL,
                  `creator_id` int(11) unsigned DEFAULT NULL,
                  `original_width` int(10) unsigned NOT NULL DEFAULT '0',
                  `original_height` int(10) unsigned NOT NULL DEFAULT '0',
                  `submitter_id` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}
