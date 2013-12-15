<?php

namespace AVCMS\Controller;

use AVCMS\Form\FormBuilder;
use AVCMS\Validation\Validator;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AVCMS\Model\ModelFactory;

abstract class Controller extends ContainerAware {

    protected $parent_namespace = "";

    /**
     * @var \AVCMS\Model\ModelFactory
     */
    protected $model_factory;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->model_factory = $container->get('model.factory');
    }

    /**
     * @param $model_name
     * @return \AVCMS\Model\Model
     */
    protected function newModel($model_name)
    {
        $full_namespace = "$this->parent_namespace\\Model\\$model_name";
        return $this->model_factory->create($full_namespace);
    }

    protected function buildForm(FormBuilder $form)
    {
        $form->setValidator( $this->newValidator() );

        return $form;
    }

    protected function newValidator()
    {
        $validator = new Validator();
        $validator->setModelFactory($this->model_factory);

        return $validator;
    }

    protected function getUser()
    {
        return $this->container->get('active.user')->getUser();
    }
}