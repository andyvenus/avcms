<?php
/**
 * User: Andy
 * Date: 16/01/2014
 * Time: 11:33
 */

namespace AV\Form;

use AV\Form\Exception\InvalidArgumentException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FormView
 * @package AV\Form
 *
 * Holds form data ready for the view and provides helper methods
 */
class FormView implements FormViewInterface
{
    /**
     * @var Array
     */
    protected $fields = array();

    /**
     * @var String
     */
    protected $action = null;

    /**
     * @var String
     */
    protected $method = 'POST';

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var String
     */
    protected $submit_button_label = 'Submit';

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var bool
     */
    protected $submitted;

    /**
     * @var array
     */
    protected $sections;

    /**
     * @var FormBlueprint
     */
    protected $form_blueprint;

    /**
     * @var bool
     */
    protected $valid;

    /**
     * @param array $fields
     * @return mixed|void
     * @throws Exception\InvalidArgumentException
     */
    public function setFields(array $fields)
    {
        if (array_key_exists('params', $fields)) {
            throw new InvalidArgumentException("Your form cannot contain a field called 'params' as it clashes with internal functions");
        }

        $this->fields = $this->doFieldTranslations($fields);
    }

    public function setSections(array $sections)
    {
        foreach ($sections as $section_id => $section) {
            $this->sections[$section_id]['label'] = $this->translate($section['label']);
        }
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function setFormBlueprint(FormBlueprintInterface $form_blueprint)
    {
        $this->form_blueprint = $form_blueprint;
    }

    public function getSuccessMessage()
    {
        if ($this->submitted) {
            return $this->form_blueprint->getSuccessMessage();
        }
        else {
            return null;
        }
    }

    public function doFieldTranslations($fields) {
        $updated_fields = array();

        foreach ($fields as $field_name => $field) {

            if (isset($field['options']['label'])) {
                $field['options']['label'] = $this->translate($field['options']['label']);
            }

            if (isset($field['options']['choices'])) {
                foreach ($field['options']['choices'] as $value => $label) {
                    $field['options']['choices'][$value] = $this->translate($field['options']['choices'][$value]);
                }
            }

            if (isset($field['fields'])) {
                $field['fields'] = $this->doFieldTranslations($field['fields']);
            }

            if (strpos($field['name'], '[]') === false) {
                $updated_fields[$field_name] = $field;
            }
            else {
                $updated_fields[] = $field;
            }
        }

        return $updated_fields;
    }

    /**
     * {@inheritdoc}
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param $section
     * @return array
     */
    public function getSectionFields($section)
    {
        $matched_fields = array();
        foreach ($this->fields as $field_name => $field) {
            if (isset($field['options']['section']) && $field['options']['section'] == $section) {
                $matched_fields[$field_name] = $field;
            }
        }

        return $matched_fields;
    }

    /**
     * {@inheritdoc}
     */
    public function setAction($url)
    {
        $this->params['action'] = $url;
    }

    public function getAction()
    {
        return (isset($this->params['action']) ? $this->params['action'] : null);
    }

    /**
     * {@inheritdoc}
     */
    public function setMethod($method)
    {
        $this->params['method'] = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->params['name'] = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setEncoding($encoding)
    {
        $this->params['encoding'] = $encoding;
    }

    public function getEncoding()
    {
        return $this->params['encoding'];
    }

    /**
     * {@inheritdoc}
     */
    public function setSubmitButtonLabel($label)
    {
        $this->submit_button_label = $label;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubmitButtonLabel()
    {
        return $this->translate($this->submit_button_label);
    }

    /**
     * @param $submitted bool
     */
    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;
    }

    /**
     * @return bool
     */
    public function isSubmitted()
    {
        return $this->submitted;
    }

    /**
     * @param $valid bool
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Inject a translator to provide label translations
     *
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Translate a string if the translator has been injected
     *
     * @param $str The string that will be translated
     * @param array $params
     * @return string
     */
    protected function translate($str, $params = array())
    {
        if (isset($this->translator)) {
            return $this->translator->trans($str, $params);
        }
        else {
            return $str;
        }
    }

    /**
     * @param $errors FormError[]
     */
    public function setErrors(array $errors)
    {
        foreach ($errors as $error) {
            if ($error->getTranslate() == true) {
                $error->setMessage($this->translate($error->getMessage(), $error->getTranslationParams()));
            }
            $this->errors[] = $error;
        }
    }

    public function hasErrors()
    {
        return isset($this->errors);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        if (isset($this->errors)) {
            return $this->errors;
        }
        else {
            return null;
        }
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getJsonResponseData()
    {
        $json['errors'] = $this->errors;
        $json['has_errors'] = $this->hasErrors();
        $json['success_message'] = $this->getSuccessMessage();

        return $json;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }
        else {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __isset($name)
    {
        if (isset($this->fields[$name])) {
            return true;
        }
        else {
            return false;
        }
    }
} 