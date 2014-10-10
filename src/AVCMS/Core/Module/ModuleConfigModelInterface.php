<?php
/**
 * User: Andy
 * Date: 19/09/2014
 * Time: 14:43
 */
namespace AVCMS\Core\Module;

interface ModuleConfigModelInterface
{
    /**
     * @param $position
     * @return ModuleConfigInterface[]
     */
    public function getPositionModuleConfigs($position);
}