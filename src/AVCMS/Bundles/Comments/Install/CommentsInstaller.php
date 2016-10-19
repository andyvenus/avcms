<?php
/**
 * User: Andy
 * Date: 24/11/14
 * Time: 18:39
 */

namespace AVCMS\Bundles\Comments\Install;

use AVCMS\Core\Installer\BundleInstaller;

class CommentsInstaller extends BundleInstaller
{
    public function getVersions()
    {
        return array(
            '1.0' => 'install_1_0_0',
            '1.0.1' => 'install_1_0_1',
        );
    }

    public function install_1_0_0()
    {
        $this->sql("
            CREATE TABLE `{$this->prefix}comments` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `content_type` varchar(30) DEFAULT NULL,
                  `content_id` varchar(40) NOT NULL DEFAULT '',
                  `content_title` text,
                  `user_id` int(11) NOT NULL,
                  `comment` text NOT NULL,
                  `date` int(11) NOT NULL DEFAULT '0',
                  `ip` varchar(15) NOT NULL DEFAULT '',
                  `thread` int(11) unsigned NOT NULL DEFAULT '0',
                  `replies` int(10) unsigned NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `content_id` (`content_id`),
                  KEY `content_type` (`content_type`),
                  KEY `user_id` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}comment_flood_control` (
                  `user_id` int(11) NOT NULL DEFAULT '0',
                  `last_comment_time` int(11) NOT NULL,
                  PRIMARY KEY (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public function install_1_0_1()
    {
        $this->PDO->exec("ALTER TABLE {$this->prefix}comments CHANGE `replies` `replies` int(10) unsigned NOT NULL DEFAULT 0");
    }
}
