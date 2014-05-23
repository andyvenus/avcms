<?php
/**
 * User: Andy
 * Date: 13/03/2014
 * Time: 14:23
 */
namespace AVCMS\Core\AssetManager\Asset;

interface BundleAssetInterface
{
    public function getType();

    public function getBundle();

    public function getFile();

    public function getDevUrl($prepend);
}