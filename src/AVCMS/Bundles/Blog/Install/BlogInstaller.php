<?php
/**
 * User: Andy
 * Date: 24/10/14
 * Time: 15:50
 */

namespace AVCMS\Bundles\Blog\Install;

use AVCMS\Core\Installer\BundleInstaller;

class BlogInstaller extends BundleInstaller
{
    public function getVersions()
    {
        return [
            '1.0' => 'install_1_0_0',
            '1.0.1' => 'install_1_0_1',
            '1.0.2' => 'install_1_0_2'
        ];
    }

    public function install_1_0_0()
    {
        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}blog_posts` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `title` text,
                  `body` longtext,
                  `user_id` int(11) DEFAULT NULL,
                  `published` tinyint(1) NOT NULL DEFAULT '1',
                  `date_added` int(11) DEFAULT NULL,
                  `date_edited` int(11) DEFAULT NULL,
                  `creator_id` int(11) DEFAULT NULL,
                  `editor_id` int(11) DEFAULT NULL,
                  `slug` text,
                  `publish_date` int(11) DEFAULT NULL,
                  `comments` int(11) NOT NULL DEFAULT '0',
                  `hits` int(11) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `slug` (`slug`(255)),
                  KEY `published` (`published`),
                  KEY `publish_date` (`publish_date`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public function install_1_0_1()
    {
        $this->PDO->exec("
             CREATE TABLE `{$this->prefix}blog_categories` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(120) DEFAULT NULL,
                  `order` int(11) DEFAULT NULL,
                  `parent` int(11) DEFAULT NULL,
                  `slug` varchar(120) DEFAULT NULL,
                  `description` text,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->PDO->exec("ALTER TABLE {$this->prefix}blog_posts ADD category_parent_id int(11) DEFAULT NULL AFTER body");

        $this->PDO->exec("ALTER TABLE {$this->prefix}blog_posts ADD `category_id` int(11) NOT NULL DEFAULT '0' AFTER body");
    }

    public function install_1_0_2()
    {
        $this->PDO->exec("ALTER TABLE {$this->prefix}blog_categories ADD parents varchar(255) DEFAULT NULL");
        $this->PDO->exec("ALTER TABLE {$this->prefix}blog_categories ADD children varchar(255) DEFAULT NULL");

        $this->PDO->exec("ALTER TABLE {$this->prefix}blog_posts DROP COLUMN category_parent_id");

        $categories = $this->modelFactory->create('AVCMS\Bundles\Blog\Model\BlogCategories');
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
