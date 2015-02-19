<?php
/**
 * User: Andy
 * Date: 25/11/14
 * Time: 21:34
 */

namespace AV\Cache;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CacheClearer
{
    protected $cacheDir;

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * @param string|array $caches
     * @param bool $keepParent
     */
    public function clearCaches(array $caches = null, $keepParent = false)
    {
        if ($caches === null) {
            $this->removeDir($this->cacheDir, $keepParent);
            return;
        }

        if (in_array('main', $caches)) {
            $dirItr = new \DirectoryIterator($this->cacheDir);
            foreach ($dirItr as $file) {
                if ($file->isFile()) {
                    unlink($file->getPathname());
                }
            }
        }

        foreach ($caches as $cacheDir) {
            $cacheDir = str_replace('..', '', $cacheDir);
            if (file_exists($this->cacheDir.'/'.$cacheDir)) {
                $this->removeDir($this->cacheDir . '/' . $cacheDir, $keepParent);
            }
        }
    }

    protected function removeDir($path, $keepParent = false)
    {
        if (!file_exists($path)) {
            return;
        }

        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $file) {
            if (in_array($file->getBasename(), array('.', '..'))) {
                continue;
            } elseif ($file->isDir()) {
                rmdir($file->getPathname());
            } elseif ($file->isFile() || $file->isLink()) {
                unlink($file->getPathname());
            }
        }
        if ($keepParent === false) {
            rmdir($path);
        }
    }
} 
