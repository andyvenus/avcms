<?php

namespace AVCMS\Core\Controller;

use AVCMS\Bundles\UsersBase\Exception\PermissionDeniedException;
use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Form\FormHandler;
use AVCMS\Core\Form\FormView;
use AVCMS\Core\Form\RequestHandler\SymfonyRequestHandler;
use AVCMS\Core\Form\ValidatorExtension\AVCMSValidatorExtension;
use AVCMS\Core\Model\EntityProcessor;
use AVCMS\Core\Security\PermissionsError;
use AVCMS\Core\Validation\Validator;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    // todo: do this proper
    protected $config = array('users_model' => 'AVBlog\Bundles\Users\Model\Users');

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->model_factory = $container->get('model.factory');
        $this->translator = $container->get('translator');
    }

    /**
     * @param $model_name
     * @return \AVCMS\Core\Model\Model|mixed
     */
    protected function model($model_name)
    {
        // If no namespace seems to be specified, use the same namespace that the controller uses
        if (strpos($model_name, '\\') === false && strpos($model_name, '@') === false) {
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

    protected function buildForm(FormBlueprint $form, $entities = array(), $request = null)
    {
        $form_handler = new FormHandler($form, new SymfonyRequestHandler(), new EntityProcessor(), null,  $this->container->get('dispatcher'));
        $form_handler->setValidator(new AVCMSValidatorExtension($this->newValidator()));
        $form_view = new FormView();
        $form_view->setTranslator($this->translator);
        $form_handler->setFormView($form_view);
        $form_handler->setTransformerManager($this->container->get('form.transformer.manager'));

        if (!is_array($entities)) {
            $entities = array($entities);
        }
        foreach ($entities as $entity) {
            $form_handler->bindEntity($entity);
        }

        if ($request) {
            $form_handler->handleRequest($request);
        }

        return $form_handler;
    }

    /**
     * @param $route
     * @param array $parameters
     * @param bool $referenceType
     * @return null|string
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->container->get('routing.url.generator')->generate($route, $parameters, $referenceType);
    }

    /**
     * @return \AVCMS\Bundles\UsersBase\ActiveUser
     */
    protected function activeUser()
    {
        return $this->container->get('active.user');
    }

    protected function checkPermission($permission)
    {
        $permission = $this->container->get('active.user')->hasPermission($permission);

        if (!$permission) {
            throw new PermissionDeniedException("You don't have permission to view this page");
        }
    }

    protected function render($template, array $context = array(), $return_response = false)
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

    public function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        return new NotFoundHttpException($message, $previous);
    }

    public function requirePermissions($permissions)
    {
        $permissions = (array) $permissions;

        foreach ($permissions as $permission) {
            if ($this->activeUser()->hasPermission($permission) == false) {
                throw new PermissionsError('You do not have authorisation to view this page', $permission);
            }
        }
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $this->container->get('dispatcher');
    }
}