<?php
/**
 * User: Andy
 * Date: 30/03/15
 * Time: 18:47
 */

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AV\Cache\CacheClearer;
use AV\Kernel\Bundle\Exception\NotFoundException;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use DirectoryIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Yaml\Yaml;

class BundlesAdminController extends AdminBaseController
{
    public function setUp()
    {
        if (!$this->isGranted('ADMIN_BUNDLES')) {
            throw new AccessDeniedException;
        }
    }

    public function manageBundlesAction()
    {
        $bundle_manager = $this->container->get('bundle_manager');

        $locations = $bundle_manager->getBundleLocations();

        $staticBundles = [];
        $toggleBundles = [];

        foreach ($locations as $location) {
            if (is_dir($location)) {
                $dir = new DirectoryIterator($location);
                foreach ($dir as $file_info) {
                    if (!$file_info->isDot()) {
                        if (file_exists($file_info->getPath().'/'.$file_info->getFilename().'/config/bundle.yml')) {
                            $config = $bundle_manager->loadBundleConfig($file_info->getFilename());
                            $config->enabled = $bundle_manager->hasBundle($config->name);

                            if ($config->toggle) {
                                $toggleBundles[] = $config;
                            }
                            else {
                                $staticBundles[] = $config;
                            }
                        }
                    }
                }
            }
        }

        return new Response($this->render('@CmsFoundation/admin/manage_bundles.twig', array('static_bundles' => $staticBundles, 'toggle_bundles' => $toggleBundles)));
    }

    public function toggleBundleAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            throw new InvalidCsrfTokenException;
        }

        $bundle = $request->get('bundle');
        $enable = (bool) $request->get('enable');

        try {
            $config = $this->get('bundle_manager')->loadBundleConfig($bundle);
        } catch (NotFoundException $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }

        if (!isset($config['toggle']) || !$config['toggle']) {
            return new JsonResponse(['success' => false, 'message' => 'Bundle cannot be toggled']);
        }

        $adminBundleConfig = $this->getParam('root_dir').'/webmaster/config/bundles.yml';

        $config = [];
        if (file_exists($adminBundleConfig)) {
            $config = Yaml::parse(file_get_contents($adminBundleConfig));
        }

        $config[$bundle]['enabled'] = $enable;

        file_put_contents($adminBundleConfig, Yaml::dump($config));

        if ($this->getParam('cache_dir')) {
            $cacheClearer = new CacheClearer($this->getParam('cache_dir'));
            $cacheClearer->clearCaches();
        }

        return new JsonResponse(['success' => true]);
    }
}
