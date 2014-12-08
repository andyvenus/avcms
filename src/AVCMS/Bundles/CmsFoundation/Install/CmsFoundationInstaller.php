<?php
/**
 * User: Andy
 * Date: 24/10/14
 * Time: 23:47
 */

namespace AVCMS\Bundles\CmsFoundation\Install;

use AVCMS\Core\Installer\BundleInstaller;

class CmsFoundationInstaller extends BundleInstaller
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
            CREATE TABLE `{$this->prefix}menus` (
                  `id` varchar(30) NOT NULL DEFAULT '',
                  `label` varchar(60) DEFAULT NULL,
                  `custom` int(11) DEFAULT '1',
                  `provider` varchar(40) DEFAULT NULL,
                  `owner` varchar(40) DEFAULT NULL,
                  `active` tinyint(1) NOT NULL DEFAULT '1',
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}menu_items` (
                  `id` varchar(60) NOT NULL DEFAULT '',
                  `menu` varchar(30) DEFAULT NULL,
                  `type` varchar(30) DEFAULT NULL,
                  `label` varchar(80) DEFAULT NULL,
                  `target` varchar(100) DEFAULT NULL,
                  `icon` varchar(100) DEFAULT NULL,
                  `parent` varchar(60) DEFAULT NULL,
                  `enabled` tinyint(1) DEFAULT '1',
                  `order` int(11) NOT NULL DEFAULT '99',
                  `owner` varchar(30) DEFAULT NULL,
                  `permission` varchar(80) DEFAULT NULL,
                  `translatable` tinyint(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`),
                  KEY `menu` (`menu`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}modules` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `title` varchar(80) NOT NULL,
                  `module` varchar(120) NOT NULL DEFAULT '',
                  `position` varchar(80) NOT NULL DEFAULT '',
                  `order` int(11) NOT NULL DEFAULT '99',
                  `settings` text,
                  `active` tinyint(1) NOT NULL DEFAULT '0',
                  `show_header` tinyint(1) NOT NULL DEFAULT '0',
                  `template_type` varchar(40) NOT NULL DEFAULT 'plain',
                  `template` varchar(200) NOT NULL DEFAULT '0',
                  `limit_routes` text NOT NULL,
                  `cache_time` int(11) DEFAULT NULL,
                  `permissions` varchar(120) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `position` (`position`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}module_positions` (
                  `id` varchar(80) NOT NULL DEFAULT '',
                  `name` varchar(80) DEFAULT NULL,
                  `description` text,
                  `type` varchar(80) DEFAULT NULL,
                  `global_modules` tinyint(1) DEFAULT '1',
                  `active` tinyint(1) DEFAULT NULL,
                  `provider` varchar(80) DEFAULT NULL,
                  `owner` varchar(80) DEFAULT NULL,
                  `environment` varchar(15) NOT NULL DEFAULT 'frontend',
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}settings` (
                  `name` varchar(50) NOT NULL DEFAULT '',
                  `value` text NOT NULL,
                  `loader` varchar(50) DEFAULT NULL,
                  `owner` varchar(50) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}