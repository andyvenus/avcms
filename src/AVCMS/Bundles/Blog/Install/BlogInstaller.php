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
            '1.0' => 'install_1_0_0'
        ];
    }

    public function install_1_0_0()
    {
        $this->PDO->exec("CREATE TABLE `{$this->prefix}blog_posts` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `title` text,
          `body` text,
          `user_id` varchar(200) DEFAULT NULL,
          `testone__something` varchar(80) DEFAULT NULL,
          `published` int(11) DEFAULT NULL,
          `date_added` int(11) DEFAULT NULL,
          `date_edited` int(11) DEFAULT NULL,
          `creator_id` int(11) DEFAULT NULL,
          `editor_id` int(11) DEFAULT NULL,
          `slug` varchar(255) DEFAULT NULL,
          `publish_date` int(11) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }
}