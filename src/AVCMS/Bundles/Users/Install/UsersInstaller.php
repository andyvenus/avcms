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
                  PRIMARY KEY (`user_id`),
                  KEY `code` (`code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}group_permissions` (
                  `role` varchar(80) DEFAULT NULL,
                  `name` varchar(80) DEFAULT NULL,
                  `value` tinyint(1) NOT NULL DEFAULT '0',
                  KEY `role` (`role`),
                  KEY `name` (`name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}password_resets` (
                  `user_id` int(11) NOT NULL,
                  `code` varchar(128) NOT NULL DEFAULT '',
                  `generated` int(11) DEFAULT NULL,
                  KEY `user_id` (`user_id`),
                  KEY `code` (`code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
                  `last_used_timestamp` int(11) DEFAULT NULL,
                  KEY `class` (`class`),
                  KEY `username` (`username`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->sql("
            CREATE TABLE `{$this->prefix}groups` (
                  `id` varchar(40) NOT NULL DEFAULT '',
                  `name` varchar(80) DEFAULT NULL,
                  `flood_control_time` int(11) NOT NULL DEFAULT '30',
                  `admin_default` varchar(11) NOT NULL DEFAULT 'deny',
                  `perm_default` varchar(11) NOT NULL DEFAULT 'deny',
                  `moderator_default` varchar(11) NOT NULL DEFAULT 'deny',
                  `owner` varchar(30) DEFAULT NULL,
                  `custom_permissions` tinyint(1) NOT NULL DEFAULT '0',
                  `admin_panel_access` tinyint(1) NOT NULL DEFAULT '0',
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
                  `joined` int(11) NOT NULL DEFAULT '0',
                  `avatar` varchar(25) NOT NULL,
                  `cover_image` varchar(25) NOT NULL,
                  `registration_ip` varchar(20) DEFAULT NULL,
                  `last_ip` varchar(20) NOT NULL DEFAULT '',
                  `last_activity` int(11) NOT NULL,
                  `timezone` varchar(80) NOT NULL DEFAULT '',
                  `slug` varchar(200) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `username` (`username`),
                  KEY `email` (`email`),
                  KEY `activate` (`email_validated`),
                  KEY `slug` (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $groupsModel = $this->modelFactory->create('AVCMS\Bundles\Users\Model\UserGroups');

        $superAdmin = $groupsModel->newEntity();
        $superAdmin->fromArray(['id' => 'ROLE_SUPER_ADMIN', 'name' => 'Super Admin', 'flood_control_time' => 0, 'admin_default' => 'allow', 'perm_default' => 'allow', 'owner' => 'app', 'custom_permissions' => '0']);
        $groupsModel->save($superAdmin);

        $admin = $groupsModel->newEntity();
        $admin->fromArray(['id' => 'ROLE_ADMIN', 'name' => 'Admin', 'flood_control_time' => 0, 'admin_default' => 'allow', 'perm_default' => 'allow', 'owner' => 'app', 'custom_permissions' => '1']);
        $groupsModel->save($admin);

        $user = $groupsModel->newEntity();
        $user->fromArray(['id' => 'ROLE_USER', 'name' => 'Member', 'flood_control_time' => 60, 'admin_default' => 'deny', 'perm_default' => 'allow', 'owner' => 'app', 'custom_permissions' => '1']);
        $groupsModel->save($user);

        $moderator = $groupsModel->newEntity();
        $moderator->fromArray(['id' => 'ROLE_MODERATOR', 'name' => 'Moderator', 'flood_control_time' => 0, 'admin_default' => 'deny', 'perm_default' => 'allow', 'owner' => 'app', 'custom_permissions' => '1']);
        $groupsModel->save($moderator);

        $notValidated = $groupsModel->newEntity();
        $notValidated->fromArray(['id' => 'ROLE_NOT_VALIDATED', 'name' => 'Not Validated', 'flood_control_time' => 0, 'admin_default' => 'deny', 'perm_default' => 'deny', 'owner' => 'app', 'custom_permissions' => '0']);
        $groupsModel->save($notValidated);

        $banned = $groupsModel->newEntity();
        $banned->fromArray(['id' => 'ROLE_BANNED', 'name' => 'Banned', 'flood_control_time' => 0, 'admin_default' => 'deny', 'perm_default' => 'deny', 'owner' => 'app', 'custom_permissions' => '0']);
        $groupsModel->save($banned);

        $unregistered = $groupsModel->newEntity();
        $unregistered->fromArray(['id' => 'ROLE_UNREGISTERED', 'name' => 'Unregistered', 'flood_control_time' => 60, 'admin_default' => 'deny', 'perm_default' => 'deny', 'owner' => 'app', 'custom_permissions' => '0']);
        $groupsModel->save($unregistered);
    }
}
