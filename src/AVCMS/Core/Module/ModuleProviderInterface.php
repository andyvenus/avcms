<?php
/**
 * User: Andy
 * Date: 10/09/2014
 * Time: 20:34
 */

namespace AVCMS\Core\Module;


interface ModuleProviderInterface {
    public function getModules();

    public function hasModule($moduleName);

    public function getModule($moduleName);

    public function getProviderId();
}