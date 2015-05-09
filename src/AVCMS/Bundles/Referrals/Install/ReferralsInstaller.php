<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 23:31
 */

namespace AVCMS\Bundles\Referrals\Install;

use AVCMS\Core\Installer\BundleInstaller;

class ReferralsInstaller extends BundleInstaller
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
            CREATE TABLE `{$this->prefix}referrals` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(120) DEFAULT NULL,
                  `inbound` int(11) NOT NULL DEFAULT '0',
                  `outbound` int(10) NOT NULL DEFAULT '0',
                  `conversions` int(11) NOT NULL DEFAULT '0',
                  `last_referral` int(11) unsigned DEFAULT NULL,
                  `user_id` int(10) unsigned DEFAULT NULL,
                  `user_email` varchar(100) DEFAULT NULL,
                  `type` varchar(40) NOT NULL DEFAULT 'user',
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public function bundleCleanup()
    {
        if (!$this->columnExists('users', 'referral__referral')) {
            $this->PDO->exec("ALTER TABLE `{$this->prefix}users` ADD `referral__referral` int(11) NOT NULL DEFAULT '0'");
        }
    }
}
