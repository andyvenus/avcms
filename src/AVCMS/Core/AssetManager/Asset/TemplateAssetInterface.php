<?php
/**
 * User: Andy
 * Date: 13/03/2014
 * Time: 14:23
 */
namespace AVCMS\Core\AssetManager\Asset;

interface TemplateAssetInterface
{
    public function getType();

    public function getEnvironment();

    public function getTemplate();

    public function getFile();

    public function getDevUrl($prepend);
}