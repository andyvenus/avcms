<?php
/**
 * User: Andy
 * Date: 07/10/2014
 * Time: 18:12
 */

namespace AV\Form\Event;

use AV\Form\FormBlueprintInterface;
use AV\Form\FormViewInterface;
use Symfony\Component\EventDispatcher\Event;

class FilterNewFormEvent extends Event
{
    /**
     * @var FormBlueprintInterface
     */
    protected $formBlueprint;

    /**
     * @var FormViewInterface
     */
    protected $formView;

    /**
     * @var mixed
     */
    protected $validator;

    public function __construct(FormBlueprintInterface $formBlueprint, $formView, $validator)
    {
        $this->formBlueprint = $formBlueprint;
        $this->formView = $formView;
        $this->validator = $validator;
    }

    /**
     * @return FormBlueprintInterface
     */
    public function getFormBlueprint()
    {
        return $this->formBlueprint;
    }

    public function setFormBlueprint(FormBlueprintInterface $formBlueprint)
    {
        $this->formBlueprint = $formBlueprint;
    }

    /**
     * @return FormViewInterface|mixed
     */
    public function getFormView()
    {
        return $this->formView;
    }

    /**
     * @param FormViewInterface|mixed $formView
     */
    public function setFormView($formView)
    {
        $this->formView = $formView;
    }

    /**
     * @return mixed
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param mixed $validator
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
    }
} 