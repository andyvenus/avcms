<?php
/**
 * User: Andy
 * Date: 30/03/15
 * Time: 12:06
 */

namespace AVCMS\Core\Kernel;

class BundleManager extends \AV\Kernel\Bundle\BundleManager
{
    protected function getConfigFiles()
    {
        $locations = parent::getConfigFiles();

        if (file_exists('webmaster/config/bundles.yml')) {
            $locations[] = 'webmaster/config/bundles.yml';
        }

        return $locations;
    }
}
