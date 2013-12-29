<?php

namespace AVCMS\Core\Controller;

use AVCMS\Core\Form\FormBuilder;
use AVCMS\Core\Validation\Validator;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AVCMS\Core\Model\ModelFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\MessageSelector;
use AVCMS\Core\Translation\Translator;

abstract class Controller extends ContainerAware {

    protected $parent_namespace = "";

    /**
     * @var \AVCMS\Core\Model\ModelFactory
     */
    protected $model_factory;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->model_factory = $container->get('model.factory');
    }

    /**
     * @param $model_name
     * @return \AVCMS\Core\Model\Model
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

        if (!isset($this->translator)) {
            $this->translator = new Translator('en_GB', new MessageSelector());
            $this->translator->addLoader('array', new ArrayLoader());
            $this->translator->addResource('array', array('That name is already in use' => 'Arr, that name be already in use'), 'en_GB');
        }

        $validator->setTranslator($this->translator);

        return $validator;
    }

    protected function getUser()
    {
        return $this->container->get('active.user')->getUser();
    }

    protected function render($template, $context, $return_response = false)
    {
        $twig = $this->container->get('twig');
        $result = $twig->render($template, $context);

        if ($return_response) {
            return new Response($result);
        }
        else {
            return $result;
        }
    }
}