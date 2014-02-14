<?php
/**
 * User: Andy
 * Date: 13/02/2014
 * Time: 13:13
 */

namespace AVCMS\Core\Form\Event;

use AVCMS\Core\Form\FormBlueprintInterface;
use AVCMS\Core\Form\FormHandler;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FormHandlerConstructEvent
 * @package AVCMS\Core\Form\Event
 */
class FormHandlerRequestEvent extends Event
{
    /**
     * @var FormHandler
     */
    protected $form_handler;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $form_data;

    /**
     * @param FormHandler $form_handler
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $form_data
     * @internal param \AVCMS\Core\Form\FormBlueprintInterface $form_blueprint
     */
    public function __construct(FormHandler $form_handler, Request $request, $form_data)
    {
        $this->setFormHandler($form_handler);
        $this->setRequest($request);
        $this->setFormData($form_data);
    }

    /**
     * @param FormHandler $form_handler
     */
    public function setFormHandler(FormHandler $form_handler)
    {
        $this->form_handler = $form_handler;
    }

    /**
     * @return FormHandler
     */
    public function getFormHandler()
    {
        return $this->form_handler;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return FormBlueprintInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param array $form_data
     */
    public function setFormData(array $form_data)
    {
        $this->form_data = $form_data;
    }

    /**
     * @return array
     */
    public function getFormData()
    {
        return $this->form_data;
    }
}