<?php
/**
 * User: Andy
 * Date: 24/11/14
 * Time: 19:06
 */

class BlogDefaultContentInstaller extends \AVCMS\Core\Installer\DefaultContentInstaller
{
    public function getHooks()
    {
        return array(
            'Blog' => ['1.0' => 'blogDefaults']
        );
    }

    public function blogDefaults()
    {

    }
}