<?php
/**
 * User: Andy
 * Date: 16/01/2014
 * Time: 14:04
 */

namespace AVCMS\Core\Form\Twig;


class FormExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @var \Twig_Compiler
     */
    protected $compiler;

    /**
     * @var \Twig_Template
     */
    protected $base_template;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
        $this->compiler = $environment->getCompiler();
        $this->base_template = $this->environment->loadTemplate('avcms_form.twig');
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
            )
        );
    }

    public function formStart($form)
    {
        return $this->base_template->renderBlock('form_start', array('form' => $form));
    }

    public function formEnd($form)
    {
        return $this->base_template->renderBlock('form_end', array('form' => $form));
    }

    public function formField($field_data)
    {
        if ($this->base_template->hasBlock($field_data['type'].'_field')) {
            return $this->base_template->renderBlock($field_data['type'].'_field', $field_data);
        }
        else {
            return "<strong>Error:</strong> No template for field type '$field_data[type]'";
        }
    }

    public function formLabel($field_data)
    {
        return $this->base_template->renderBlock('form_label', $field_data);
    }

    public function formSubmitButton($form)
    {
        $label = $form->getSubmitButtonLabel();
        return $this->base_template->renderBlock('submit_button', array('label' => $label));
    }

    public function formRow($field)
    {
        if ($field['type'] == 'hidden') {
            $block = 'hidden_row';
        }
        else {
            $block = 'form_row';
        }

        return $this->base_template->renderBlock($block, array('form_row' => $field));
    }

    public function formRows($form)
    {
        $rows = $form->getFields();

        return $this->base_template->renderBlock('form_rows', array('form_rows' => $rows));
    }
}