<?php
/**
 * User: Andy
 * Date: 24/11/14
 * Time: 18:50
 */

namespace AVCMS\Bundles\Reports\Install;

use AVCMS\Core\Installer\BundleInstaller;

class ReportsInstaller extends BundleInstaller
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
            CREATE TABLE `{$this->prefix}reports` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `sender_user_id` int(11) DEFAULT NULL,
                  `reported_user_id` int(11) DEFAULT NULL,
                  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                  `content_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
                  `content_id` varchar(30) NOT NULL DEFAULT '',
                  `date` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `content_type` (`content_type`)
)           ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}