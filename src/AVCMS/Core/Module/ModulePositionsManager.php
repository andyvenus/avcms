<?php
/**
 * User: Andy
 * Date: 30/09/2014
 * Time: 12:57
 */

namespace AVCMS\Core\Module;

use AVCMS\Core\Model\Model;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ModulePositionsManager
{
    /**
     * @var ModulePositionsProviderInterface[]
     */
    protected $providers;

    /**
     * @param Model $positionsModel
     */
    public function __construct(Model $positionsModel)
    {
        $this->positionsModel = $positionsModel;
    }

    public function getPosition($position)
    {
        return $this->positionsModel->getOne($position);
    }

    /**
     * @param $positionProvider
     */
    public function setProvider($positionProvider)
    {
        $this->providers[] = $positionProvider;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function updatePositions(GetResponseEvent $event = null)
    {
        if ($event && !$event->isMasterRequest()) {
            return;
        }

        foreach ($this->providers as $provider) {
            $provider->updatePositions($this);
        }
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->positionsModel;
    }
} 