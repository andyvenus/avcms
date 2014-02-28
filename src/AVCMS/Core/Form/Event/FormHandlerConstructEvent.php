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

/**
 * Class FormHandlerConstructEvent
 * @package AVCMS\Core\Form\Event
 *
 * Event called within FormHandler::__construct()
 */
class FormHandlerConstructEvent extends Event
{
    /**
     * @var FormHandler
     */
    protected $form_handler;

    /**
     * @var FormBlueprintInterface
     */
    protected $form_blueprint;

    /**
     * @param FormHandler $form_handler
     * @param FormBlueprintInterface $form_blueprint
     */
    public function __construct(FormHandler $form_handler, FormBlueprintInterface $form_blueprint)
    {
        $this->setFormHandler($form_handler);
        $this->setFormBlueprint($form_blueprint);
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
     * @param FormBlueprintInterface $form_blueprint
     */
    public function setFormBlueprint(FormBlueprintInterface $form_blueprint)
    {
        $this->form_blueprint = $form_blueprint;
    }

    /**
     * @return FormBlueprintInterface
     */
    public function getFormBlueprint()
    {
        return $this->form_blueprint;
    }
}