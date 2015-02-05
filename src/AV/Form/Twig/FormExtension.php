<?php
/**
 * User: Andy
 * Date: 16/01/2014
 * Time: 14:04
 */

namespace AV\Form\Twig;

use AV\Form\FormViewInterface;

class FormExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @var \Twig_Template
     */
    protected $baseTemplate;

    /**
     * @var string
     */
    protected $defaultTemplate;

    public function __construct($defaultTemplate = '@Form/bootstrap_form.twig')
    {
        $this->defaultTemplate = $defaultTemplate;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
        $this->baseTemplate = $this->environment->loadTemplate($this->defaultTemplate);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'avcms_form';
    }

    public function getFunctions()
    {
        return array(
            'form_field' => new \Twig_SimpleFunction('form_field',
                    array($this, 'formField'),
                    array('is_safe' => array('html')
                )
            ),
            'form_label' => new \Twig_SimpleFunction('form_label',
                    array($this, 'formLabel'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_start' => new \Twig_SimpleFunction('form_start',
                    array($this, 'formStart'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_end' => new \Twig_SimpleFunction('form_end',
                    array($this, 'formEnd'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_submit_button' => new \Twig_SimpleFunction('form_submit_button',
                    array($this, 'formSubmitButton'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_row' => new \Twig_SimpleFunction('form_row',
                    array($this, 'formRow'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_rows' => new \Twig_SimpleFunction('form_rows',
                    array($this, 'formRows'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_rows_before' => new \Twig_SimpleFunction('form_rows_before',
                    array($this, 'formRowsBefore'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_rows_after' => new \Twig_SimpleFunction('form_rows_after',
                    array($this, 'formRowsAfter'),
                    array('is_safe' => array('html')
                    )
            ),
            'form_messages' => new \Twig_SimpleFunction('form_messages',
                array($this, 'formMessages'),
                array('is_safe' => array('html')
            )),
            'form' => new \Twig_SimpleFunction('form',
                array($this, 'form'),
                array('is_safe' => array('html')
            ))
        );
    }

    public function formStart(FormViewInterface $form, $attributes = array(), $template = null)
    {
        if ($template === null) {
            $this->baseTemplate = $this->environment->loadTemplate($this->defaultTemplate);
        }
        else {
            $this->baseTemplate = $this->environment->loadTemplate($template);
        }

        return $this->baseTemplate->renderBlock('form_start', array('form' => $form, 'attr' => $attributes));
    }

    public function setFormTemplate($template)
    {
        $this->baseTemplate = $this->environment->loadTemplate($template);
    }

    public function formEnd(FormViewInterface $form)
    {
        return $this->baseTemplate->renderBlock('form_end', array('form' => $form));
    }

    public function formField($fieldData, $attributes = array())
    {
        if (isset($fieldData['options']['attr']) && is_array($fieldData['options']['attr'])) {
            $fieldData['attr'] = array_merge($fieldData['options']['attr'], $attributes);
        }
        else {
            $fieldData['attr'] = $attributes;
        }

        if (isset($fieldData['options']['field_template'])) {
            $template = $this->environment->loadTemplate($fieldData['options']['field_template']);

            unset($fieldData['options']['field_template']);
            $field = $template->render(array('field' => $fieldData));
        }
        else {
            $field = $this->baseTemplate->renderBlock($fieldData['type'].'_field', $fieldData);
        }

        if (!$field) {
            return "<strong>Error:</strong> No template for field type '$fieldData[type]'";
        }

        return $field;
    }

    public function formLabel($fieldData)
    {
        return $this->baseTemplate->renderBlock('form_label', $fieldData);
    }

    public function formSubmitButton(FormViewInterface $form)
    {
        $label = $form->getSubmitButtonLabel();
        return $this->baseTemplate->renderBlock('submit_button', array('label' => $label));
    }

    public function formRow($field, $attributes = array())
    {
        if ($field['type'] == 'hidden' || $field['type'] == 'collection') {
            $block = 'hidden_row';
        }
        elseif ($field['type'] == 'checkbox') {
            $block = 'checkbox_row';
        }
        else {
            $block = 'form_label_row';
        }

        return $this->baseTemplate->renderBlock($block, array('form_row' => $field, 'attr' => $attributes));
    }

    public function formRows(FormViewInterface $form, $attributes = array())
    {
        $fields = $form->getFields();

        return $this->baseTemplate->renderBlock('form_rows', array('form_rows' => $fields, 'attr' => $attributes));
    }

    public function formRowsBefore(FormViewInterface $form, $beforeField, $attributes = array())
    {
        $fields = $form->getFields();

        $limitedFields = array();

        foreach ($fields as $fieldName => $field) {
            if ($fieldName == $beforeField) {
                break;
            }
            else {
                $limitedFields[$fieldName] = $field;
            }
        }

        return $this->baseTemplate->renderBlock('form_rows', array('form_rows' => $limitedFields, 'attr' => $attributes));
    }

    public function formRowsAfter(FormViewInterface $form, $beforeField, $attributes = array())
    {
        $fields = $form->getFields();

        $beforeFieldReached = false;
        $limitedFields = array();

        foreach ($fields as $fieldName => $field) {
            if ($beforeFieldReached) {
                $limitedFields[$fieldName] = $field;
            }

            if ($fieldName == $beforeField) {
                $beforeFieldReached = true;
            }
        }

        return $this->baseTemplate->renderBlock('form_rows', array('form_rows' => $limitedFields, 'attr' => $attributes));
    }

    public function form(FormViewInterface $form, $attributes = array(), $template = null)
    {
        return $this->baseTemplate->renderBlock('form_complete', array('form' => $form, 'attributes' => $attributes, 'template' => $template));
    }

    public function formMessages(FormViewInterface $form)
    {
        return $this->baseTemplate->renderBlock('messages', array('form' => $form));
    }
}
