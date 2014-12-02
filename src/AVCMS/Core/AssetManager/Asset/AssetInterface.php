<?php
/**
 * User: Andy
 * Date: 02/12/14
 * Time: 11:13
 */

namespace AVCMS\Core\AssetManager\Asset;

interface AssetInterface
{
    public function getType();

    public function getFilename();

    public function getDevUrl($prepend);
} 