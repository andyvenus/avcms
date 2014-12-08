<?php
/**
 * User: Andy
 * Date: 26/07/2014
 * Time: 18:37
 */

namespace AVCMS\BundlesDev\BundleManager\Controller;

use DirectoryIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class ManageBundlesController extends BundleBaseController
{
    public function homeAction()
    {
        $bundle_manager = $this->container->get('bundle_manager');

        $locations = $bundle_manager->getBundleLocations();

        $bundles = array();

        foreach ($locations as $location) {
            if (is_dir($location)) {
                $dir = new DirectoryIterator($location);
                foreach ($dir as $file_info) {
                    if (!$file_info->isDot()) {
                        if (file_exists($file_info->getPath().'/'.$file_info->getFilename().'/config/bundle.yml')) {
                            $config = $bundle_manager->loadBundleConfig($file_info->getFilename());
                            $config->active = $bundle_manager->hasBundle($config->name);
                            $bundles[] = $config;
                        }
                    }
                }
            }
        }

        return new Response($this->render('@BundleManager/manage_bundles.twig', array('bundles' => $bundles)));
    }

    public function manageBundleAction($bundle)
    {
        $bundle_manager = $this->container->get('bundle_manager');

        if (!$bundle_config = $this->getBundleConfig($bundle)) {
            return new Response('Bundle not found');
        }

        $bundle_enabled = $bundle_manager->hasBundle($bundle_config->name);

        $routes_location = $bundle_config->directory.'/config/routes.yml';

        $bundle_routes = array();
        if (file_exists($routes_location)) {
            $bundle_routes_yaml = Yaml::parse(file_get_contents($routes_location));
            if (!empty($bundle_routes_yaml)) {
                foreach ($bundle_routes_yaml as $route_name => $route) {
                    $route['link_route'] = (strpos($route['path'], '{') === false && $bundle_enabled);

                    $bundle_routes[$route_name] = $route;
                }
            }
        }

        $content_types_location = $bundle_config->directory.'/config/generated_content.yml';

        $content = array();
        if (file_exists($content_types_location)) {
            $content = Yaml::parse(file_get_contents($content_types_location));
        }

        return new Response($this->render('@BundleManager/manage_bundle.twig', array(
            'bundle_enabled' => $bundle_enabled,
            'bundle_config' => $bundle_config,
            'routes' => $bundle_routes,
            'bundle_content' => $content,
            'bundle_manager' => $bundle_manager
        )));
    }

    public function toggleBundleEnabledAction(Request $request)
    {
        $bundle = $request->get('bundle');
        $status = $request->get('status');

        if (!$bundle_config = $this->getBundleConfig($bundle)) {
            return new Response('Bundle not found');
        }

        $appDir = $this->container->getParameter('app_dir');
        $app_bundles_config = Yaml::parse(file_get_contents($appDir.'/config/bundles.yml'));
        $bundle_config = array();
        if (isset($app_bundles_config[$bundle])) {
            $bundle_config = $app_bundles_config[$bundle];
        }

        $bundle_config['enabled'] = ($status == 'enable' ? true : false);

        $app_bundles_config[$bundle] = $bundle_config;

        file_put_contents($appDir.'/config/bundles.yml', Yaml::dump($app_bundles_config, 10));

        return new Response('');
    }
}