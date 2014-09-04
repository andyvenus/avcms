<?php
/**
 * User: Andy
 * Date: 03/09/2014
 * Time: 13:51
 */

namespace AVCMS\Core\Content;

use AVCMS\Core\Form\FormHandler;
use AVCMS\Core\Model\Entity;
use AVCMS\Core\Model\Model;
use Symfony\Component\HttpFoundation\Request;

class EditContentHelper
{
    protected $model;

    /**
     * @var \AVCMS\Core\Model\Entity|mixed
     */
    protected $entity;

    /**
     * @var \AVCMS\Core\Form\FormHandler
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    protected $form_submitted = false;

    protected $form_valid = false;

    public function __construct(Model $model, FormHandler $form, Entity $entity = null)
    {
        $this->model = $model;
        $this->form = $form;
        $this->entity = $entity;
    }

    public function handleRequest(Request $request)
    {
        $this->request = $request;

        if ($this->entity === null) {
            $id = $this->request->attributes->get('id', 0);
            $this->entity = $this->model->getOneOrNew($id);
        }

        if ($this->form !== null) {
            $this->form->bindEntity($this->entity);
            $this->form->handleRequest($request);
        }
    }

    public function handleRequestAndSave($request)
    {
        $this->handleRequest($request);

        return $this->save();
    }

    public function save()
    {
        if (!isset($this->request)) {
            throw new \Exception("Cannot save, request not handled");
        }

        if ($this->form->isValid() && $this->contentExists()) {
            $this->form->saveToEntities();
            $this->model->save($this->entity);

            return true;
        }
        else {
            return false;
        }
    }

    public function contentExists()
    {
        return (!is_null($this->entity));
    }

    public function formSubmitted()
    {
        return $this->form->isSubmitted();
    }

    public function formValid()
    {
        return $this->form->isValid();
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function jsonResponseData($redirect)
    {
        return array(
            'form' => $this->form->createView()->getJsonResponseData(),
            'redirect' => $redirect,
            'id' => $this->entity->getId()
        );
    }
}