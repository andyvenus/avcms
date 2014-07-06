<?php
/**
 * User: Andy
 * Date: 30/06/2014
 * Time: 18:44
 */

namespace AVCMS\Core\Controller\Event;

use AVCMS\Core\Form\FormHandler;
use AVCMS\Core\Model\Entity;
use AVCMS\Core\Model\Model;
use Symfony\Component\EventDispatcher\Event;

class AdminSaveContentEvent extends Event
{
    /**
     * @var \AVCMS\Core\Model\Entity
     */
    protected $entity;

    /**
     * @var \AVCMS\Core\Model\Model
     */
    protected $model;

    /**
     * @var \AVCMS\Core\Form\FormHandler
     */
    protected $form;

    public function __construct(Entity $entity, Model $model, FormHandler $form)
    {
        $this->entity = $entity;
        $this->model = $model;
        $this->form = $form;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return FormHandler
     */
    public function getForm()
    {
        return $this->form;
    }
} 