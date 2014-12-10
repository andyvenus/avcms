<?php
/**
 * User: Andy
 * Date: 18/10/14
 * Time: 15:07
 */

namespace AVCMS\Bundles\Installer\Controller;

use AV\Cache\CacheClearer;
use AV\Form\FormError;
use AVCMS\Bundles\Installer\Form\CreateAdminForm;
use AVCMS\Bundles\Installer\Form\NewInstallForm;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\SlugGenerator\SlugGenerator;
use Cocur\Slugify\Slugify;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

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
        return $this->redirect($this->generateUrl('new_install'));
    }

    public function newInstallAction(Request $request)
    {
        $databaseConfigFile = $this->container->getParameter('main_app_dir').'/config/database.php';

        // Secure, if database.php already exists only run update-bundles
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

    public function createAdminAction(Request $request)
    {
        $users = $this->model('AVCMS\Bundles\Users\Model\Users');

        // Secure, admin can't be created here unless there are 0 users in the database
        if ($users->query()->count() !== 0) {
            return new RedirectResponse('../admin/');
        }

        $newUser = $users->newEntity();

        $form = $this->buildForm(new CreateAdminForm(), $request, $newUser);

        if ($form->isValid() && $form->getData('password1') === $form->getData('password2')) {
            $form->saveToEntities();
            $encoder = new BCryptPasswordEncoder(9);
            $newUser->setPassword($encoder->encodePassword($form->getData('password1'), null));
            $newUser->setRoleList('ROLE_SUPER_ADMIN');
            $newUser->setJoined(time());
            $newUser->setLastIp($request->getClientIp());

            $slugGen = new Slugify();
            $newUser->setSlug($slugGen->slugify($newUser->getUsername()));

            $users->save($newUser);

            return new RedirectResponse('../');
        }
        elseif ($form->getData('password1') !== $form->getData('password2')) {
            $form->addCustomErrors([new FormError('password2', 'The two passwords you entered did not match')]);
        }

        return new Response($this->render('@Installer/create_admin.twig', ['form' => $form->createView()]));
    }

    public function updateBundlesAction(Request $request)
    {
        $installType = $request->get('install_type', 'update');

        if ($installType == 'update') {
            $nextPage = '../admin/';
        }
        else {
            $nextPage = 'home';
        }

        $updateBundles = $this->getInstaller()->getBundlesRequiringUpdate();

        return new Response($this->render('@Installer/update_bundles.twig', ['update_bundles' => $updateBundles, 'next_page_url' => $nextPage]));
    }

    public function updateBundleAction(Request $request)
    {
        $bundle = $request->get('bundle');

        $success = $this->getInstaller()->updateBundle($bundle);

        $cacheClearer = new CacheClearer('cache');
        $cacheClearer->clearCaches();

        return new JsonResponse(['success' => $success, 'error' => $this->getInstaller()->getFailureError()]);
    }
}