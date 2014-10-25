<?php
/**
 * User: Andy
 * Date: 25/10/14
 * Time: 10:23
 */

namespace AVCMS\Bundles\Users\Install;

use AVCMS\Core\Installer\BundleInstaller;

class UsersInstaller extends BundleInstaller
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
            CREATE TABLE `{$this->prefix}email_validations` (
                  `user_id` int(11) NOT NULL,
                  `code` varchar(120) DEFAULT NULL,
                  `generated` int(11) DEFAULT NULL,
                  PRIMARY KEY (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}group_permissions` (
                  `role` varchar(80) DEFAULT NULL,
                  `name` varchar(80) DEFAULT NULL,
                  `value` tinyint(1) NOT NULL DEFAULT '0'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}password_resets` (
                  `user_id` int(11) NOT NULL,
                  `code` varchar(128) NOT NULL DEFAULT '',
                  `generated` int(11) DEFAULT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}permissions` (
                  `id` varchar(80) NOT NULL DEFAULT '',
                  `name` varchar(80) DEFAULT NULL,
                  `description` varchar(320) DEFAULT NULL,
                  `loader` varchar(30) DEFAULT NULL,
                  `owner` varchar(30) DEFAULT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}sessions` (
                  `class` varchar(255) DEFAULT NULL,
                  `username` varchar(255) DEFAULT NULL,
                  `series` varchar(255) DEFAULT NULL,
                  `token_value` varchar(255) DEFAULT NULL,
                  `last_used_timestamp` int(11) DEFAULT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}groups` (
                  `id` varchar(40) NOT NULL DEFAULT '',
                  `name` varchar(80) DEFAULT NULL,
                  `flood_control_time` int(11) NOT NULL DEFAULT '30',
                  `admin_default` varchar(11) NOT NULL DEFAULT '',
                  `perm_default` varchar(11) NOT NULL DEFAULT '',
                  `owner` varchar(30) DEFAULT NULL,
                  `custom_permissions` tinyint(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}users` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `username` varchar(200) NOT NULL DEFAULT '',
                  `password` varchar(128) NOT NULL DEFAULT '',
                  `email` varchar(120) NOT NULL DEFAULT '',
                  `email_validated` tinyint(1) NOT NULL,
                  `about` varchar(200) NOT NULL DEFAULT '',
                  `role_list` varchar(120) NOT NULL DEFAULT 'ROLE_USER',
                  `location` varchar(50) NOT NULL DEFAULT '',
                  `interests` text NOT NULL,
                  `website` varchar(200) NOT NULL DEFAULT '',
                  `joined` text NOT NULL,
                  `avatar` varchar(25) NOT NULL,
                  `facebook` tinyint(1) NOT NULL DEFAULT '0',
                  `facebook_id` bigint(20) unsigned NOT NULL,
                  `last_ip` char(15) NOT NULL DEFAULT '',
                  `slug` varchar(200) NOT NULL DEFAULT '',
                  `last_activity` datetime NOT NULL,
                  `timezone` varchar(80) NOT NULL DEFAULT '',
                  `cover_image` varchar(25) NOT NULL DEFAULT '',
                  PRIMARY KEY (`id`),
                  KEY `slug` (`slug`),
                  KEY `username` (`username`),
                  KEY `email` (`email`),
                  KEY `email_validated` (`email_validated`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
    }
}