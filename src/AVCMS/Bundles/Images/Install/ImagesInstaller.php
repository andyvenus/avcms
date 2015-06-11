<?php
/**
 * User: Andy
 * Date: 01/03/15
 * Time: 16:03
 */

namespace AVCMS\Bundles\Images\Install;

use AVCMS\Core\Installer\BundleInstaller;

class ImagesInstaller extends BundleInstaller
{
    public function getVersions()
    {
        return [
            '1.0' => 'install_1_0_0',
        ];
    }

    public function install_1_0_0()
    {
        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}image_collections` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL DEFAULT '',
                  `description` text NOT NULL,
                  `category_id` int(11) unsigned NOT NULL,
                  `category_parent_id` int(11) unsigned DEFAULT '0',
                  `hits` int(11) unsigned NOT NULL DEFAULT '0',
                  `last_hit` int(11) DEFAULT NULL,
                  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
                  `publish_date` int(11) DEFAULT NULL,
                  `thumbnail` text NOT NULL,
                  `featured` tinyint(1) NOT NULL,
                  `date_added` int(11) NOT NULL DEFAULT '0',
                  `date_edited` int(11) NOT NULL DEFAULT '0',
                  `creator_id` int(11) DEFAULT NULL,
                  `editor_id` int(11) DEFAULT NULL,
                  `slug` varchar(255) NOT NULL DEFAULT '',
                  `submitter_id` int(11) NOT NULL DEFAULT '0',
                  `likes` int(11) NOT NULL DEFAULT '0',
                  `dislikes` int(11) NOT NULL DEFAULT '0',
                  `comments` int(11) unsigned NOT NULL DEFAULT '0',
                  `type` varchar(30) NOT NULL DEFAULT 'default',
                  `downloadable` varchar(30) NOT NULL DEFAULT 'default',
                  `total_images` int(11) DEFAULT '1',
                  `import_folder` varchar(260) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `seo_url` (`slug`),
                  KEY `category_id` (`category_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->PDO->exec("
            CREATE TABLE `{$this->prefix}image_files` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `url` text,
                  `caption` text,
                  `collection_id` int(11) DEFAULT NULL,
                  `import_folder` text,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        $this->PDO->exec("
            CREATE TABLE `{$this->prefix}image_submissions` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL DEFAULT '',
                  `description` text NOT NULL,
                  `category_id` int(11) unsigned NOT NULL,
                  `date_added` int(11) NOT NULL DEFAULT '0',
                  `creator_id` int(11) DEFAULT NULL,
                  `submitter_id` int(11) NOT NULL DEFAULT '0',
                  `slug` varchar(200) DEFAULT NULL,
                  `total_images` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->PDO->exec("
            CREATE TABLE `{$this->prefix}image_submission_files` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `url` text,
                  `caption` text,
                  `collection_id` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}image_categories` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(120) DEFAULT NULL,
                  `order` int(11) DEFAULT NULL,
                  `parent` int(11) DEFAULT NULL,
                  `slug` varchar(120) DEFAULT NULL,
                  `description` text,
                  PRIMARY KEY (`id`),
                  KEY `seo_url` (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}
