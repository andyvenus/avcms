<?php
/**
 * User: Andy
 * Date: 30/09/2014
 * Time: 13:01
 */

namespace AVCMS\Core\Module;

interface ModulePositionsProviderInterface
{
    public function updatePositions(ModulePositionsManager $modulePositionsManager);
} 