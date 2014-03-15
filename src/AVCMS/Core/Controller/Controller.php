<?php

namespace AVCMS\Core\Controller;

use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Form\FormHandler;
use AVCMS\Core\Form\FormView;
use AVCMS\Core\Form\RequestHandler\SymfonyRequestHandler;
use AVCMS\Core\Form\ValidatorExtension\AVCMSValidatorExtension;
use AVCMS\Core\Model\FormEntityProcessor;
use AVCMS\Core\Validation\Validator;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\MessageSelector;
use AVCMS\Core\Translation\Translator;

abstract class Controller extends ContainerAware {

    protected $parent_namespace = "";

    protected $translator;

    /**
     * @var \AVCMS\Core\Model\ModelFactory
     */
    protected $model_factory;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->model_factory = $container->get('model.factory');
        // TODO: Remove this
        $this->translator = new Translator('en_GB', new MessageSelector());
        $this->translator->addLoader('array', new ArrayLoader());
        $this->translator->addResource('array',
            array(
                'That name is already in use' => 'Arr, that name be already in use',
                'Name' => 'FRUNCH NAME',
                'Cat One' => 'Le Category Une',
                'Published' => 'Pubèlishé',
                'Submit' => 'Procesèur',
                'Cannot find an account that has that username or email address' => 'Oh vue du nuet finel the user',
                'Title' => 'Oh qui le Titlè'
            ),
            'en_GB'
        );
    }

    /**
     * @param $model_name
     * @return \AVCMS\Core\Model\Model|mixed
     */
    protected function model($model_name)
    {
        // If no namespace seems to be specified, use the same namespace that the controller uses
        if (!strpos($model_name, '\\')) {
            $class = get_class($this);
            $namespace = substr($class, 0, strpos($class, '\\Controller'));

            $model_name = "$namespace\\Model\\$model_name";
        }
        return $this->model_factory->create($model_name);
    }

    protected function newValidator()
    {
        $validator = new Validator();
        $validator->setModelFactory($this->model_factory);

        $validator->setTranslator($this->translator);

        return $validator;
    }

    protected function buildForm(FormBlueprint $form)
    {
        $form_handler = new FormHandler($form, new SymfonyRequestHandler(), new FormEntityProcessor(), $this->container->get('dispatcher'));
        $form_handler->setValidatior(new AVCMSValidatorExtension($this->newValidator()));
        $form_view = new FormView();
        $form_view->setTranslator($this->translator);
        $form_handler->setFormView($form_view);

        return $form_handler;
    }

    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->container->get('routing.url.generator')->generate($route, $parameters, $referenceType);
    }

    /**
     * @return \AVCMS\Bundles\UsersBase\ActiveUser
     */
    protected function getActiveUser()
    {
        return $this->container->get('active.user');
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

    protected function get($service)
    {
        return $this->container->get($service);
    }
}