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

    protected function getInstaller()
    {
        return $this->container->get('installer');
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
                $conn = new \Pixie\Connection('mysql', $databaseConfig);
            }
            catch (\PDOException $e) {
                $form->addCustomErrors([new FormError('all', 'The database details you entered to not appear to be valid.')]);
                $form->addCustomErrors([new FormError('all', $e->getMessage())]);
            }

            if ($form->isValid()) {
                file_put_contents($databaseConfigFile, '<?php return ' . var_export($databaseConfig, true) . ';');

                $conn->getPdoInstance()->exec("
                    CREATE TABLE `{$databaseConfig['prefix']}versions` (
                          `id` varchar(30) NOT NULL DEFAULT '',
                          `installed_version` varchar(30) NOT NULL DEFAULT '',
                          `type` varchar(30) NOT NULL DEFAULT '',
                          PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                ");

                return $this->redirect($this->generateUrl('update_bundles'));
            }
        }

        return new Response($this->render('@Installer/new_install.twig', ['form' => $form->createView()]));
    }

    public function updateBundlesAction(Request $request)
    {
        $installType = $request->get('install_type', 'update');

        $updateBundles = $this->getInstaller()->getBundlesRequiringUpdate();

        return new Response($this->render('@Installer/update_bundles.twig', ['update_bundles' => $updateBundles, 'install_type' => $installType]));
    }

    public function updateBundleAction(Request $request)
    {
        $bundle = $request->get('bundle');

        $success = $this->getInstaller()->updateBundle($bundle);

        return new JsonResponse(['success' => $success, 'error' => $this->getInstaller()->getFailureError()]);
    }
}