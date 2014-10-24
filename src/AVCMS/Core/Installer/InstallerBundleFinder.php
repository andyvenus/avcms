<?php
/**
 * User: Andy
 * Date: 24/10/14
 * Time: 12:55
 */

namespace AVCMS\Core\Installer;

use DirectoryIterator;

class InstallerBundleFinder
{
    public function findBundles(array $dirs)
    {
        $bundles = [];
        foreach ($dirs as $dir) {
            foreach (new DirectoryIterator($dir) as $bundleDir) {
                if($bundleDir->isDot()) continue;

                if ($bundleDir->isDir()) {
                    if (file_exists($bundleDir->getRealPath().'/Install')) {
                        $bundles[] = $bundleDir->getRealPath();
                    }
                }
            }
        }

        return $bundles;
    }
}