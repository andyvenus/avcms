<?php
/**
 * User: Andy
 * Date: 01/03/15
 * Time: 16:03
 */

namespace AVCMS\Bundles\Games\Install;

use AVCMS\Core\Installer\BundleInstaller;

class GamesInstaller extends BundleInstaller
{
    public function getVersions()
    {
        return [
            '1.0' => 'install_1_0_0',
            '1.0.1' => 'install_1_0_1',
            '1.0.2' => 'install_1_0_2',
            '1.0.3' => 'install_1_0_3'
        ];
    }

    public function install_1_0_0()
    {
        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}games` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL DEFAULT '',
                  `description` text NOT NULL,
                  `file` text,
                  `category_id` int(11) unsigned NOT NULL,
                  `category_parent_id` int(11) unsigned DEFAULT '0',
                  `hits` int(11) unsigned NOT NULL DEFAULT '0',
                  `last_hit` int(11) DEFAULT NULL,
                  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
                  `publish_date` int(11) DEFAULT NULL,
                  `width` int(11) unsigned NOT NULL,
                  `height` int(11) unsigned NOT NULL,
                  `thumbnail` text NOT NULL,
                  `instructions` text NOT NULL,
                  `featured` tinyint(1) NOT NULL,
                  `date_added` int(11) NOT NULL DEFAULT '0',
                  `date_edited` int(11) NOT NULL DEFAULT '0',
                  `creator_id` int(11) DEFAULT NULL,
                  `editor_id` int(11) DEFAULT NULL,
                  `advert_id` int(11) NOT NULL DEFAULT '1',
                  `slug` varchar(255) NOT NULL DEFAULT '',
                  `submitter_id` int(11) NOT NULL DEFAULT '0',
                  `embed_code` text,
                  `likes` int(11) NOT NULL DEFAULT '0',
                  `dislikes` int(11) NOT NULL DEFAULT '0',
                  `comments` int(11) unsigned NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`),
                  KEY `seo_url` (`slug`),
                  KEY `category_id` (`category_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}feed_games` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL DEFAULT '',
                  `description` text NOT NULL,
                  `file` text NOT NULL,
                  `category` varchar(200) DEFAULT NULL,
                  `width` int(11) unsigned NOT NULL,
                  `height` int(11) unsigned NOT NULL,
                  `thumbnail` text NOT NULL,
                  `instructions` text NOT NULL,
                  `tags` text,
                  `provider` varchar(40) DEFAULT NULL,
                  `provider_id` varchar(120) DEFAULT NULL,
                  `status` varchar(15) NOT NULL DEFAULT 'pending',
                  `downloadable` int(11) NOT NULL DEFAULT '1',
                  `file_type` varchar(40) DEFAULT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}game_categories` (
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
             CREATE TABLE `{$this->prefix}game_embeds` (
                  `extension` varchar(15) NOT NULL DEFAULT '',
                  `template` varchar(140) DEFAULT NULL,
                  PRIMARY KEY (`extension`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}game_feed_categories` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `keyword` varchar(40) DEFAULT NULL,
                  `category_id` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->PDO->exec("
            INSERT INTO `{$this->prefix}game_embeds` (`extension`, `template`)
            VALUES
                ('dcr','@Games/embeds/shockwave.twig'),
                ('swf','@Games/embeds/flash.twig'),
                ('unity3d','@Games/embeds/unity.twig')
        ");
    }

    public function install_1_0_1()
    {
        $this->PDO->exec("
            INSERT INTO `{$this->prefix}game_embeds` (`extension`, `template`)
            VALUES ('html_embed','@Games/embeds/html.twig')
        ");

        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}game_submissions` (
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
                  `width` int(11) DEFAULT NULL,
                  `height` int(11) DEFAULT NULL,
                  `slug` varchar(200) DEFAULT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public function install_1_0_2()
    {
        $this->PDO->exec("ALTER TABLE {$this->prefix}games CHANGE `advert_id` `advert_id` int(11) NOT NULL DEFAULT '0'");
    }

    public function install_1_0_3()
    {
        $this->PDO->exec("ALTER TABLE {$this->prefix}game_categories ADD parents varchar(255) DEFAULT NULL");
        $this->PDO->exec("ALTER TABLE {$this->prefix}game_categories ADD children varchar(255) DEFAULT NULL");

        $this->PDO->exec("ALTER TABLE {$this->prefix}games DROP COLUMN category_parent_id");

        $categories = $this->modelFactory->create('AVCMS\Bundles\Games\Model\GameCategories');
        $allCategories = $categories->getAll();

        foreach ($allCategories as $category) {
            $category->setParents($category->getParent());

            $categoryChildrenIds = [];
            foreach ($allCategories as $anotherCategory) {
                if ($anotherCategory->getParent() == $category->getId()) {
                    $categoryChildrenIds[] = $anotherCategory->getId();
                }
            }

            $category->setChildren($categoryChildrenIds);

            $categories->save($category);
        }
    }
}
