<?php
/**
 * User: Andy
 * Date: 26/11/14
 * Time: 15:25
 */

namespace AVCMS\Core\View\ModuleProvider;

use AVCMS\Bundles\CmsFoundation\Model\ModulePosition;
use AVCMS\Core\Module\ModulePositionsManager;
use AVCMS\Core\Module\ModulePositionsProviderInterface;
use AVCMS\Core\View\TemplateManager;

class TemplateModulePositionsProvider implements ModulePositionsProviderInterface
{
    const PROVIDER_NAME = 'template';

    private $templateManager;

    public function __construct(TemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    public function updatePositions(ModulePositionsManager $modulePositionsManager)
    {
        $model = $modulePositionsManager->getModel();

        $model->disablePositionsByProvider(self::PROVIDER_NAME);

        $config = $this->templateManager->getTemplateConfig();

        if (isset($config['module_positions'])) {
            foreach ($config['module_positions'] as $positionId => $positionConfig) {
                $position = $model->getOne($positionId);

                if (!$position) {
                    $position = new ModulePosition;
                }

                $position->setId($positionId);
                $position->fromArray($positionConfig);
                $position->setActive(1);
                $position->setProvider(self::PROVIDER_NAME);
                $position->setOwner($config['details']['name']);

                $model->save($position);
            }
        }
    }
}