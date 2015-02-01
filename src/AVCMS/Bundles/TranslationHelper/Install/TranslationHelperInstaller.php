<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 23:34
 */

namespace AVCMS\Bundles\TranslationHelper\Install;

use AVCMS\Core\Installer\BundleInstaller;

class TranslationHelperInstaller extends BundleInstaller
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
            CREATE TABLE `{$this->prefix}translations` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(130) DEFAULT NULL,
                  `language` char(2) DEFAULT NULL,
                  `country` char(2) DEFAULT NULL,
                  `user_id` int(11) DEFAULT NULL,
                  `downloads` int(11) NOT NULL DEFAULT '0',
                  `total_translated` int(11) NOT NULL DEFAULT '0',
                  `public` int(11) NOT NULL DEFAULT '0',
                  `creator_id` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}
