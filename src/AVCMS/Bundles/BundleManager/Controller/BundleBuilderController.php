<?php
/**
 * User: Andy
 * Date: 26/07/2014
 * Time: 16:08
 */

namespace AVCMS\Bundles\BundleManager\Controller;

use AVCMS\Bundles\BundleManager\Form\DatabaseSelectForm;
use AVCMS\Bundles\BundleManager\Form\NewBundleForm;
use AVCMS\Bundles\BundleManager\Form\NewContentForm;
use AVCMS\Core\Bundle\BundleBuilder\FileMaker;
use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class BundleBuilderController extends BundleBaseController
{
    public function homeAction()
    {
        return new Response($this->render('@dev/index.twig'));
    }

    public function newBundleAction(Request $request)
    {
        $config = $this->getBundleBuilderConfig();

        $form = $this->buildForm(new NewBundleForm(), $request);

        if ($form->isValid()) {
            $bundle_location = $form->getData('location') . '/' . $form->getData('name');
            if (file_exists($bundle_location)) {
                $form->addCustomErrors(array(new FormError('location', 'The specified directory already exists')));
            }
            else {
                mkdir($bundle_location, 0777, true);

                foreach ($config['directory_structure'] as $directory) {
                    mkdir($bundle_location.'/'.$directory, 0777);
                }

                $yaml = $form->getData();
                unset($yaml['location']);
                $yaml['namespace'] .= '\\'.$yaml['name'];

                file_put_contents($bundle_location.'/config/bundle.yml', Yaml::dump($yaml));
                file_put_contents($bundle_location.'/config/routes.yml', '');

                return $this->redirect($this->generateUrl($this->bundle->route->manage_bundle, array('bundle' => $form->getData('name'))));
            }
        }

        return new Response($this->render('@dev/new_bundle.twig', array('form' => $form->createView())));
    }

    public function addContentAction(Request $request, $bundle)
    {
        $config = $this->getBundleConfig($bundle);
        $qb = $this->container->get('query.builder');

        // Step 1, select the database table
        if (!$request->request->has('database_table')) {

            $tables_result = $qb->query("show tables")->get(null, \PDO::FETCH_BOTH);

            $tables = array();
            foreach ($tables_result as $table_result) {
                $tables[$table_result[0]] = str_replace($qb->getTablePrefix(), '', $table_result[0]);
            }

            $form = $this->buildForm(new DatabaseSelectForm($tables));

            return new Response($this->render('@dev/bundle_new_content.twig', array('database_select_form' => $form->createView(), 'bundle_config' => $config)));
        }
        // Step 2, select the database columns
        else {
            $table = $request->request->get('database_table'); //todo MMMMMM mysqlsequre
            $columns_result = $qb->query("show columns from $table")->get(null, \PDO::FETCH_ASSOC);

            $columns = array();
            foreach($columns_result as $column) {
                $columns[$column['Field']] = array(
                    'getter' => 'get'.$this->dashesToCamelCase($column['Field'], true),
                    'setter' => 'set'.$this->dashesToCamelCase($column['Field'], true),
                    'type' => $column['Type']
                );
            }

            $new_content_form = new NewContentForm($table);
            $new_content_form->addColumnsFields($columns);

            $form = $this->buildForm($new_content_form, $request);

            // Step 3 - Generate the files
            if ($form->isValid()) {
                $fm = new FileMaker($config, $table, $form->getData('plural'), $form->getData('singular'));
                $fm->setFileBasePath('src/AVCMS/Core/Bundle/BundleBuilder/BundleBlueprint/files');
                $fm->addFile('AdminController.php.bt', 'Controller/{{uc_plural}}AdminController.php');
                $fm->processAndSaveFiles(true);
            }

            return new Response($this->render('@dev/bundle_new_content_part2.twig', array('columns' => $columns, 'database_columns_form' => $form->createView(), 'bundle_config' => $config)));
        }
    }
}