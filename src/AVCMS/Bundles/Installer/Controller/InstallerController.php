<?php
/**
 * User: Andy
 * Date: 18/10/14
 * Time: 15:07
 */

namespace AVCMS\Bundles\Installer\Controller;

use AV\Form\FormError;
use AVCMS\Bundles\Installer\Form\NewInstallForm;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Installer\InstallerBundleFinder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $this->installer = $this->container->get('installer');
    }

    public function installerHomeAction()
    {
        $bf = new InstallerBundleFinder();
        $bf->findBundles(['src/AV/Bundles', 'src/AVCMS/Bundles']);

        return new Response('Hello world');
    }

    public function newInstallAction(Request $request)
    {
        $databaseConfigFile = 'app/config/database.php';

        if (file_exists($databaseConfigFile)) {
            return $this->redirect($this->generateUrl('update_bundles'));
        }

        $form = $this->buildForm(new NewInstallForm(), $request);

        if ($form->isValid()) {
            $databaseConfig = array(
                'driver'    => 'mysql', // Db driver
                'charset'   => 'utf8', // Optional
                'collation' => 'utf8_unicode_ci', // Optional
            );

            $databaseConfig['host'] = $form->getData('host');
            $databaseConfig['database'] = $form->getData('database');
            $databaseConfig['username'] = $form->getData('username');
            $databaseConfig['password'] = $form->getData('password');
            $databaseConfig['prefix'] = $form->getData('prefix');

            try {
                new \Pixie\Connection('mysql', $databaseConfig);
            }
            catch (\PDOException $e) {
                $form->addCustomErrors([new FormError('all', 'The database details you entered to not appear to be valid.')]);
                $form->addCustomErrors([new FormError('all', $e->getMessage())]);
            }

            if ($form->isValid()) {
                file_put_contents($databaseConfigFile, '<?php return ' . var_export($databaseConfig, true) . ';');
                return $this->redirect($this->generateUrl('update_bundles'));
            }
        }

        return new Response($this->render('@Installer/new_install.twig', ['form' => $form->createView()]));
    }

    public function updateBundlesAction(Request $request)
    {
        $installType = $request->get('install_type', 'update');

        $updateBundles = $this->installer->getBundlesRequiringUpdate();

        return new Response($this->render('@Installer/update_bundles.twig', ['update_bundles' => $updateBundles, 'install_type' => $installType]));
    }

    public function updateBundleAction(Request $request)
    {
        $bundle = $request->get('bundle');

        $success = $this->installer->updateBundle($bundle);

        return new JsonResponse(['success' => $success, 'error' => $this->installer->getFailureError()]);
    }
}