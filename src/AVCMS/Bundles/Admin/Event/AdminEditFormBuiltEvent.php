<?php
/**
 * User: Andy
 * Date: 30/06/2014
 * Time: 18:44
 */

namespace AVCMS\Bundles\Admin\Event;

use AV\Form\FormHandler;
use AV\Model\Entity;
use AV\Model\Model;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminEditFormBuiltEvent
 * @package AVCMS\Core\Controller\Event
 *
 * Event called after the form blueprint has been sent to the form handler
 *
 * Good for modifying the data held in the form before it's displayed or
 * before the data is saved to an entity
 */
class AdminEditFormBuiltEvent extends Event
{
    /**
     * @var \AV\Model\Entity
     */
    protected $entity;

    /**
     * @var \AV\Model\Model
     */
    protected $model;

    /**
     * @var \AV\Form\FormHandler
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Entity $entity
     * @param Model $model
     * @param FormHandler $form
     * @param Request $request
     */
    public function __construct(Entity $entity, Model $model, FormHandler $form, Request $request)
    {
        $this->entity = $entity;
        $this->model = $model;
        $this->form = $form;
        $this->request = $request;
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

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
