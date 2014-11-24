<?php
/**
 * User: Andy
 * Date: 24/10/14
 * Time: 15:49
 */

namespace AVCMS\Core\Installer;

abstract class BundleInstaller extends InstallerBase
{
    abstract public function getVersions();
}