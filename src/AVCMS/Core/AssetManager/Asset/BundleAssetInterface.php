<?php
/**
 * User: Andy
 * Date: 13/03/2014
 * Time: 14:23
 */
namespace AVCMS\Core\AssetManager\Asset;

interface BundleAssetInterface extends AssetInterface
{
    public function getBundle();
}