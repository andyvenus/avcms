<?php
/**
 * User: Andy
 * Date: 18/01/2014
 * Time: 16:15
 */
namespace AV\Form;

/**
 * Interface FormViewInterface
 * @package AV\FormBlueprint
 *
 * Interface that the view can use to get form data
 */
interface FormViewInterface
{

    /**
     * Set the form fields
     *
     * @param array $fields
     * @return mixed
     */
    public function setFields(array $fields);

    /**
     * @return array
     */
    public function getFields();

    /**
     * Set the URL the forum submits to
     *
     * @param string $url
     * @return null
     */
    public function setAction($url);

    /**
     * Set the form submit method (either POST or GET)
     *
     * @param string $method
     * @return null
     */
    public function setMethod($method);

    /**
     * Set the label for the submit button
     *
     * @param $label
     * @return mixed
     */
    public function setSubmitButtonLabel($label);

    /**
     * Get the submit button label
     *
     * @return mixed
     */
    public function getSubmitButtonLabel();

    /**
     * Check if a form field is set (magic method)
     *
     * @param $name
     * @return mixed
     */
    public function __isset($name);

    /**
     * Get a form field (returns null if not set)
     *
     * @param $name
     * @return mixed
     */
    public function __get($name);
}