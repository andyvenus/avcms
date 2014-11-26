<?php
/**
 * User: Andy
 * Date: 24/11/14
 * Time: 19:09
 */

namespace AVCMS\Core\Installer;

abstract class DefaultContentInstaller extends InstallerBase
{
    abstract function getHooks();

    public function handleContent($bundle, $version)
    {
        $hooks = $this->getHooks();

        if (isset($hooks[$bundle]) && isset($hooks[$bundle][$version])) {
            call_user_func([$this, $hooks[$bundle][$version]]);
        }
    }
}