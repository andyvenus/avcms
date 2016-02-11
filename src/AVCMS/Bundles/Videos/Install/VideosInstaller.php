<?php
/**
 * User: Andy
 * Date: 01/03/15
 * Time: 16:03
 */

namespace AVCMS\Bundles\Videos\Install;

use AVCMS\Core\Installer\BundleInstaller;

class VideosInstaller extends BundleInstaller
{
    public function getVersions()
    {
        return [
            '1.0' => 'install_1_0_0',
            '1.0.1' => 'install_1_0_1',
        ];
    }

    public function install_1_0_0()
    {
        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}videos` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL DEFAULT '',
                  `description` text NOT NULL,
                  `file` text,
                  `category_id` int(11) unsigned NOT NULL,
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
                  `advert_id` int(11) NOT NULL DEFAULT '0',
                  `slug` varchar(255) NOT NULL DEFAULT '',
                  `submitter_id` int(11) NOT NULL DEFAULT '0',
                  `embed_code` text,
                  `likes` int(11) NOT NULL DEFAULT '0',
                  `dislikes` int(11) NOT NULL DEFAULT '0',
                  `comments` int(11) unsigned NOT NULL DEFAULT '0',
                  `duration` varchar(8) DEFAULT NULL,
                  `duration_seconds` int(11) DEFAULT NULL,
                  `provider` varchar(40) DEFAULT NULL,
                  `provider_id` varchar(120) DEFAULT NULL,
                  `tags` text,
                  PRIMARY KEY (`id`),
                  KEY `seo_url` (`slug`),
                  KEY `category_id` (`category_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}video_categories` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(120) DEFAULT NULL,
                  `order` int(11) DEFAULT NULL,
                  `parent` int(11) DEFAULT NULL,
                  `slug` varchar(120) DEFAULT NULL,
                  `description` text,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}video_submissions` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL DEFAULT '',
                  `description` text NOT NULL,
                  `instructions` text NOT NULL,
                  `file` text,
                  `category_id` int(11) unsigned NOT NULL,
                  `thumbnail` text NOT NULL,
                  `date_added` int(11) NOT NULL DEFAULT '0',
                  `creator_id` int(11) DEFAULT NULL,
                  `submitter_id` int(11) NOT NULL DEFAULT '0',
                  `slug` varchar(200) DEFAULT NULL,
                  `provider` varchar(40) DEFAULT NULL,
                  `provider_id` varchar(120) DEFAULT NULL,
                  `duration` varchar(8) DEFAULT NULL,
                  `duration_seconds` int(11) DEFAULT NULL,
                  `tags` text,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $imageCategories = $this->modelFactory->create('AVCMS\Bundles\Videos\Model\VideoCategories');

        $firstCategory = $imageCategories->newEntity();
        $firstCategory->setName('First Category');
        $firstCategory->setDescription('A first category to get going. Edit me or delete me!');
        $firstCategory->setSlug('first-category');

        $imageCategories->insert($firstCategory);
    }

    public function install_1_0_1()
    {
        $this->PDO->exec("ALTER TABLE {$this->prefix}video_categories ADD parents varchar(255) DEFAULT NULL");
        $this->PDO->exec("ALTER TABLE {$this->prefix}video_categories ADD children varchar(255) DEFAULT NULL");
    }
}
