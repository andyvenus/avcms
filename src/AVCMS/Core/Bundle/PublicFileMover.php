<?php
/**
 * User: Andy
 * Date: 26/11/14
 * Time: 11:38
 */

namespace AVCMS\Core\Bundle;

use AV\Kernel\Bundle\BundleManagerInterface;
use AVCMS\Core\View\TemplateManager;

class PublicFileMover
{
    private $bundleManager;

    private $templateManager;

    private $cacheDir;

    private $ignore = array('templates', 'translations');

    public function __construct(BundleManagerInterface $bundleManager, TemplateManager $templateManager, $cacheDir = 'cache')
    {
        $this->bundleManager = $bundleManager;
        $this->templateManager = $templateManager;
        $this->cacheDir = $cacheDir;
    }

    public function doMove($forceMove = false)
    {
        $cacheFile = $this->cacheDir.'/public_asset_last_move.txt';

        $lastTime = 0;

        if ($forceMove === false && file_exists($cacheFile) && $this->bundleManager->cacheIsFresh()) {
            $lastTime = file_get_contents($cacheFile);
        }

        $bundles = $this->bundleManager->getBundleConfigs();

        foreach ($bundles as $bundle) {
            if (file_exists($bundle->directory.'/resources')) {
                $this->copyDirectory($bundle->directory.'/resources', 'web/resources/'.$bundle->name, $lastTime, $this->ignore);
            }
        }

        $templateDir = $this->templateManager->getCurrentTemplate();

        foreach (new \DirectoryIterator($templateDir) as $templateResourceDir) {
            if ($templateResourceDir->isDir() && file_exists('web/resources/'.$templateResourceDir) && $templateResourceDir->isDot() === false) {
                $this->copyDirectory($templateResourceDir->getRealPath(), 'web/resources/'.$templateResourceDir, $lastTime, $this->ignore);
            }
        }

        file_put_contents($cacheFile, time());
    }

    private function copyDirectory($src, $dst, $lastTime, array $ignore = null) {
        $dir = opendir($src);

        if (!file_exists($dst)) {
            mkdir($dst);
        }

        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' ) && ($ignore == null || !in_array($file, $ignore))) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->copyDirectory($src . '/' . $file, $dst . '/' . $file, $lastTime, $ignore);
                }
                elseif (filemtime($src . '/' . $file) > $lastTime) {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
} 