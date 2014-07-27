<?php
/**
 * User: Andy
 * Date: 26/07/2014
 * Time: 18:37
 */

namespace AVCMS\Bundles\BundleManager\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class ManageBundlesController extends BundleBaseController
{
    public function manageBundleAction($bundle)
    {
        if (!$bundle_config = $this->getBundleConfig($bundle)) {
            return new Response('Bundle not found');
        }

        $routes_location = $bundle_config->directory.'/config/routes.yml';

        $bundle_routes = array();
        if (file_exists($routes_location)) {
            $bundle_routes = Yaml::parse(file_get_contents($routes_location));
        }

        return new Response($this->render('@dev/manage_bundle.twig', array('bundle_config' => $bundle_config, 'routes' => $bundle_routes)));
    }
}