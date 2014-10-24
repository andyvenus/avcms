<?php

namespace AVCMS\Core\Controller;

use AV\Kernel\Bundle\BundleConfig;
use AV\Form\FormBlueprint;
use AV\Form\FormHandler;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AVCMS\Core\Translation\Translator;

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
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url The URL to redirect to
     * @param int $status The status code to use for the Response
     *
     * @param null $flashMessageType
     * @param null $flashMessage
     * @return RedirectResponse
     */
    public function redirect($url, $status = 302, $flashMessageType = null, $flashMessage = null)
    {
        if ($flashMessage && $flashMessageType) {
            $this->container->get('session');
            $this->get('session')->getFlashBag()->add($flashMessageType, $flashMessage);
        }

        return new RedirectResponse($url, $status);
    }

    /**
     * Get a database model
     *
     * @param $model_name
     * @return \AV\Model\Model|mixed
     */
    protected function model($model_name)
    {
        if (!isset($this->modelFactory)) {
            $this->modelFactory = $this->container->get('model_factory');
        }

        // If no namespace seems to be specified, use the same namespace that the controller uses
        if (strpos($model_name, '\\') === false && strpos($model_name, '@') === false) {
            $class = get_class($this);
            $namespace = substr($class, 0, strpos($class, '\\Controller'));

            $model_name = "$namespace\\Model\\$model_name";
        }
        return $this->modelFactory->create($model_name);
    }

    /**
     * Build a form
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
        return $this->container->get('security.context')->getToken()->getUser();
    }

    /**
     * Render a twig template
     *
     * @param $template
     * @param array $context
     * @param bool $return_response
     * @return string|Response
     */
    protected function render($template, array $context = array(), $return_response = false)
    {
        if (isset($this->bundle)) {
            $context['bundle'] = $this->bundle;
        }

        if ($this->container->has('security.context')) {
            $context['user'] = $this->container->get('security.context')->getToken()->getUser();
        }

        if (isset($this->settings)) {
            $context['settings'] = $this->settings;
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
        return $this->container->get('security.context')->isGranted($attributes, $object);
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
}