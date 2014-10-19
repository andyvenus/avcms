<?php
/**
 * User: Andy
 * Date: 30/06/2014
 * Time: 18:44
 */

namespace AVCMS\Bundles\Admin\Event;

use AV\Form\FormHandler;
use AVCMS\Core\Model\Entity;
use AVCMS\Core\Model\Model;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminSaveContentEvent
 * @package AVCMS\Core\Controller\Event
 *
 * Called AFTER content has been saved in the AdminController::edit method
 * Good for doing post-save tasks like saving taxonomy values to the database
 */
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
     * @var \AV\Form\FormHandler
     */
    protected $form;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param Entity $entity
     * @param Model $model
     * @param FormHandler $form
     */
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