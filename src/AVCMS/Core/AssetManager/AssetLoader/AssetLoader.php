<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 10:34
 */

namespace AVCMS\Core\AssetManager\AssetLoader;

abstract class AssetLoader implements AssetLoaderInterface
{
    /**
     * Work out if a file is a css or javascript file
     *
     * @param $file
     * @return string
     */
    protected function getFiletype($file)
    {
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if ($ext == 'js') {
            return 'javascript';
        }
        else if (in_array($ext, array('css', 'scss', 'sass', 'less'))) {
            return 'css';
        }
        else {
            return 'unknown';
        }
    }
} 