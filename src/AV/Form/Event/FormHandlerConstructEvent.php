<?php
/**
 * User: Andy
 * Date: 13/02/2014
 * Time: 13:13
 */

namespace AV\Form\Event;

use AV\Form\FormBlueprintInterface;
use AV\Form\FormHandler;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FormHandlerConstructEvent
 * @package AV\Form\Event
 *
 * Event called within FormHandler::__construct()
 */
class FormHandlerConstructEvent extends Event
{
    /**
     * @var FormHandler
     */
    protected $formHandler;

    /**
     * @var FormBlueprintInterface
     */
    protected $formBlueprint;

    /**
     * @param FormHandler $formHandler
     * @param FormBlueprintInterface $formBlueprint
     */
    public function __construct(FormHandler $formHandler, FormBlueprintInterface $formBlueprint)
    {
        $this->setFormHandler($formHandler);
        $this->setFormBlueprint($formBlueprint);
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
     * @param FormBlueprintInterface $formBlueprint
     */
    public function setFormBlueprint(FormBlueprintInterface $formBlueprint)
    {
        $this->formBlueprint = $formBlueprint;
    }

    /**
     * @return FormBlueprintInterface
     */
    public function getFormBlueprint()
    {
        return $this->formBlueprint;
    }
}