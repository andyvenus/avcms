<?php

namespace AVCMS\Core\Controller;

use AV\Form\FormBlueprint;
use AV\Form\FormHandler;
use AV\Kernel\Bundle\BundleConfig;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

abstract class Controller extends ContainerAware
{
    /**
     * @var \AVCMS\Core\Translation\Translator
     */
    protected $translator;

    /**
     * @var \AV\Model\ModelFactory
     */
    protected $modelFactory;

    /**
     * @var \AV\Kernel\Bundle\BundleConfig
     */
    protected $bundle;

    /**
     * @var \AVCMS\Core\SettingsManager\SettingsManager
     */
    protected $settings;

    /**
     * Set the container into this controller.
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        if ($container->has('translator')) {
            $this->translator = $container->get('translator');
        }

        if ($container->has('settings_manager')) {
            $this->settings = $container->get('settings_manager');
        }
    }

    /**
     * @param BundleConfig $bundle
     */
    public function setBundle(BundleConfig $bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * Returns a RedirectResponse to the given route.
     *
     * @param $route string #Route
     * @param $parameters
     * @param int $status The status code to use for the Response
     *
     * @param null $flashMessageType
     * @param null $flashMessage
     * @return RedirectResponse
     */
    public function redirect($route, $parameters = [], $status = 302, $flashMessageType = null, $flashMessage = null)
    {
        $url = $this->generateUrl($route, $parameters);

        if ($flashMessage && $flashMessageType) {
            $this->container->get('session');
            $this->get('session')->getFlashBag()->add($flashMessageType, $flashMessage);
        }

        return new RedirectResponse($url, $status);
    }

    /**
     * Get a database model
     *
     * You can use just the base class name (without namespace) if the Model is in the
     * same bundle as the calling controller. You can also use the base class name if
     * the model is in a bundle of the same name.
     *
     * Examples:
     *
     * "Users"              Will look for [ControllerBundleNamespace]/Model/Users and if not found
     *                      it will see if there's a bundle called "Users" and try to find
     *                      [UsersBundleNamespace]/Model/Users
     *
     * "Blog:Users"         Will find a model called "Users" in the bundle "Blog"
     *
     * "Namespace\Users"    Will load the explicitly requested model class
     *
     * @param $modelName
     * @return \AV\Model\Model|mixed
     */
    protected function model($modelName)
    {
        if (!isset($this->modelFactory)) {
            $this->modelFactory = $this->container->get('model_factory');
        }

        // If no namespace seems to be specified, use the same bundle namespace
        if (strpos($modelName, '\\') === false && strpos($modelName, '@') === false && strpos($modelName, ':') === false) {
            $originalName = $modelName;
            $namespace = $this->bundle->namespace;

            $modelName = "$namespace\\Model\\$modelName";

            if (!class_exists($modelName)) {
                try {
                    $config = $this->container->get('bundle_manager')->getBundleConfig($originalName);
                } catch (\Exception $e) {
                    // doing nothing
                }

                if (isset($config)) {
                    $modelName = $config->namespace.'\\Model\\'.$originalName;
                }
            }
        }
        return $this->modelFactory->create($modelName);
    }

    /**
     * Uses a FormBlueprint to build a FormHandler instance.
     *
     * If a request is passed in the form will automatically handle the request meaning
     * you can then use the isSubmitted method to check if the form was submitted or not.
     *
     * @param FormBlueprint $form
     * @param null $request
     * @param array|mixed $entities
     * @return FormHandler
     */
    protected function buildForm(FormBlueprint $form, $request = null, $entities = array())
    {
        $form_handler = $this->get('form.builder')->buildForm($form, $request, $entities);

        return $form_handler;
    }

    /**
     * Generate the URL to a route
     *
     * @param $route
     * @param array $parameters
     * @param bool $referenceType
     * @return null|string
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->container->get('router')->getGenerator()->generate($route, $parameters, $referenceType);
    }

    /**
     * @return mixed
     */
    protected function activeUser()
    {
        return $this->container->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * Render a twig template
     *
     * @param $template string #Template
     * @param array $context
     * @param bool $return_response
     * @return string|Response
     */
    protected function render($template, array $context = array(), $return_response = false)
    {
        if (isset($this->bundle)) {
            $context['bundle'] = $this->bundle;
        }

        $twig = $this->container->get('twig');
        $result = $twig->render($template, $context);

        if ($return_response) {
            return new Response($result);
        }
        else {
            return $result;
        }
    }

    /**
     * Get a service from the container
     *
     * @param $service
     * @return object
     */
    protected function get($service)
    {
        return $this->container->get($service);
    }

    /**
     * Get a parameter from the container
     *
     * @param $paramName
     * @return mixed
     */
    protected function getParam($paramName)
    {
        return $this->container->getParameter($paramName);
    }

    /**
     * Create a not found exception that can be thrown when a requested page doesn't exist
     *
     * @param string $message
     * @param \Exception $previous
     * @return NotFoundHttpException
     */
    public function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        return new NotFoundHttpException($message, $previous);
    }

    /**
     * Gets a setting
     *
     * @param $name
     * @return mixed
     */
    protected function setting($name)
    {
        return $this->settings->getSetting($name);
    }

    /**
     * Dispatch an EventDispatcher Event
     *
     * @param $name
     * @param Event $event
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected function dispatchEvent($name, Event $event)
    {
        return $this->container->get('dispatcher')->dispatch($name, $event);
    }

    /**
     * Check if the logged in user has certain permissions
     *
     * @param $attributes
     * @param null $object
     * @return bool
     */
    protected function isGranted($attributes, $object = null)
    {
        return $this->container->get('security.auth_checker')->isGranted($attributes, $object);
    }

    /**
     * Translate a string
     *
     * @param $id
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     * @return string
     */
    protected function trans($id, $parameters = array(), $domain = null, $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * Check if a user is logged in
     *
     * @return bool
     */
    protected function userLoggedIn()
    {
        return $this->isGranted([AuthenticatedVoter::IS_AUTHENTICATED_FULLY, AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED]);
    }
}
