<?php
/**
 * User: Andy
 * Date: 30/09/2014
 * Time: 13:15
 */

namespace AVCMS\Core\Bundle\ModuleProvider;

use AV\Kernel\Bundle\BundleManagerInterface;
use AVCMS\Bundles\CmsFoundation\Model\ModulePosition;
use AVCMS\Core\Module\ModulePositionsManager;
use AVCMS\Core\Module\ModulePositionsProviderInterface;

class BundleModulePositionsProvider implements ModulePositionsProviderInterface
{
    const PROVIDER_NAME = 'bundle';

    private $forceRefresh = false;

    public function __construct(BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;
    }

    public function updatePositions(ModulePositionsManager $modulePositionsManager)
    {
        if ($this->bundleManager->cacheIsFresh() && !$this->forceRefresh) {
            return;
        }

        $model = $modulePositionsManager->getModel();

        $model->disablePositionsByProvider(self::PROVIDER_NAME);

        foreach ($this->bundleManager->getBundleConfigs() as $bundleConfig) {
            if (isset($bundleConfig['module_positions'])) {
                foreach ($bundleConfig['module_positions'] as $positionId => $positionConfig) {
                    $position = $model->getOne($positionId);

                    if (!$position) {
                        $position = new ModulePosition;
                    }

                    $position->setId($positionId);
                    $position->fromArray($positionConfig);
                    $position->setActive(1);
                    $position->setProvider(self::PROVIDER_NAME);
                    $position->setOwner($bundleConfig->name);

                    $model->save($position);
                }
            }
        }
    }
}