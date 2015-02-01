<?php
/**
 * User: Andy
 * Date: 31/01/15
 * Time: 23:08
 */

namespace AVCMS\Bundles\Adverts\Install;

use AVCMS\Core\Installer\BundleInstaller;

class AdvertsInstaller extends BundleInstaller
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
             CREATE TABLE `{$this->prefix}adverts` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(120) DEFAULT NULL,
                  `content` text,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}
