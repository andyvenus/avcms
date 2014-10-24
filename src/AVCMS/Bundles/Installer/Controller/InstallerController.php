<?php
/**
 * User: Andy
 * Date: 18/10/14
 * Time: 15:07
 */

namespace AVCMS\Bundles\Installer\Controller;

use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Installer\InstallerBundleFinder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InstallerController extends Controller
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $installer;

    public function setUp()
    {
        //$this->installer = $this->container->get('installer');
    }

    public function installerHomeAction()
    {
        $bf = new InstallerBundleFinder();
        $bf->findBundles(['src/AV/Bundles', 'src/AVCMS/Bundles']);

        return new Response('Hello world');
    }

    public function checkForUpdatesAction()
    {
        $updateBundles = $this->installer->getBundlesRequiringUpdate();

        $this->render('@Install/updates_list.twig', ['bundles' => $updateBundles]);
    }

    public function doUpdate(Request $request)
    {
        // request lists the bundles we are updating

        // will call updateBundle via ajax with each bundle
    }

    public function updateBundle($bundleName)
    {
        // called via ajax on the doUpdate page for each bundle
    }
}