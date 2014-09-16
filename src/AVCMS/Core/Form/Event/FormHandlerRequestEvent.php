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
    protected $formHandler;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $formData;

    /**
     * @param FormHandler $formHandler
     * @param mixed $request
     * @param $formData
     * @internal param \AVCMS\Core\Form\FormBlueprintInterface $form_blueprint
     */
    public function __construct(FormHandler $formHandler, $request, $formData)
    {
        $this->setFormHandler($formHandler);
        $this->setRequest($request);
        $this->setFormData($formData);
    }

    /**
     * @param FormHandler $formHandler
     */
    public function setFormHandler(FormHandler $formHandler)
    {
        $this->formHandler = $formHandler;
    }

    /**
     * @return FormHandler
     */
    public function getFormHandler()
    {
        return $this->formHandler;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
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
     * @param array $formData
     */
    public function setFormData(array $formData)
    {
        $this->formData = $formData;
    }

    /**
     * @return array
     */
    public function getFormData()
    {
        return $this->formData;
    }
}