<?php
/**
 * User: Andy
 * Date: 03/07/2014
 * Time: 11:17
 */

namespace AVCMS\Fields;

class ContentField
{
    /**
     * @var string|null The field form class
     */
    protected $form;

    /**
     * @var string|null The field tasks class
     */
    protected $tasks;

    public function getForm()
    {
        return $this->form;
    }

    public function getEvents()
    {
        return $this->tasks;
    }

    public function createTasks($field_config)
    {
        return new $this->tasks($field_config);
    }

    public function createForm($field_config)
    {
        return new $this->form($field_config);
    }

    public function setFormClass($form_class)
    {
        $this->form = $form_class;
    }

    public function setTasksClass($tasks_class)
    {
        $this->form = $tasks_class;
    }
} 