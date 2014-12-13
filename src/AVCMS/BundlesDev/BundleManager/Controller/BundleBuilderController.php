<?php
/**
 * User: Andy
 * Date: 26/07/2014
 * Time: 16:08
 */

namespace AVCMS\BundlesDev\BundleManager\Controller;

use AV\Form\FormError;
use AVCMS\BundlesDev\BundleManager\Form\DatabaseSelectForm;
use AVCMS\BundlesDev\BundleManager\Form\NewBundleForm;
use AVCMS\BundlesDev\BundleManager\Form\NewContentForm;
use AVCMS\Core\Bundle\BundleBuilder\CodeGenerator\PhpClass;
use AVCMS\Core\Bundle\BundleBuilder\CodeGenerator\PhpMethod;
use AVCMS\Core\Bundle\BundleBuilder\CodeGenerator\Visitor;
use AVCMS\Core\Bundle\BundleBuilder\FileMaker;
use CG\Core\DefaultGeneratorStrategy;
use CG\Generator\PhpParameter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class BundleBuilderController extends BundleBaseController
{
    public function newBundleAction(Request $request)
    {
        $config = $this->getBundleBuilderConfig();
        $appDir = $this->container->getParameter('app_dir');

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
                unset($yaml['location'], $yaml['_csrf_token']);
                $yaml['namespace'] .= '\\'.ucfirst($yaml['name']);
                $yaml['require'] = array('Framework');

                file_put_contents($bundle_location.'/config/bundle.yml', Yaml::dump($yaml));
                file_put_contents($bundle_location.'/config/routes.yml', '');
                file_put_contents($bundle_location.'/config/admin_routes.yml', '');

                $app_bundles_config = Yaml::parse(file_get_contents($this->container->getParameter('config_dir').'/bundles.yml'));
                $app_bundles_config[ucfirst($yaml['name'])] = array('enabled' => false);

                file_put_contents($appDir.'/config/bundles.yml', Yaml::dump($app_bundles_config));

                return $this->redirect($this->generateUrl('manage_bundle', array('bundle' => $form->getData('name'))));
            }
        }

        return new Response($this->render('@BundleManager/new_bundle.twig', array('form' => $form->createView())));
    }

    public function addContentAction(Request $request, $bundle)
    {
        $config = $this->getBundleConfig($bundle);
        $qb = $this->container->get('query_builder');

        // Step 1, select the database table
        if (!$request->request->has('database_table')) {

            $tables_result = $qb->query("show tables")->get(null, \PDO::FETCH_BOTH);

            $tables = array();
            foreach ($tables_result as $table_result) {
                $tables[$table_result[0]] = str_replace($qb->getTablePrefix(), '', $table_result[0]);
            }

            $form = $this->buildForm(new DatabaseSelectForm($tables));

            return new Response($this->render('@BundleManager/bundle_new_content.twig', array('database_select_form' => $form->createView(), 'bundle_config' => $config)));
        }
        // Step 2, select the database columns
        else {
            $table = preg_replace('/[^0-9a-zA-Z_]/', '', $request->request->get('database_table'));
            $columns_result = $qb->query("show columns from $table")->get(null, \PDO::FETCH_ASSOC);

            $columns = array();
            foreach($columns_result as $column) {
                $columns[$column['Field']] = array(
                    'getter' => 'get'.$this->dashesToCamelCase($column['Field'], true),
                    'setter' => 'set'.$this->dashesToCamelCase($column['Field'], true),
                    'type' => $column['Type']
                );
            }

            $new_content_form = new NewContentForm(true, $table);
            $new_content_form->addColumnsFields($columns);

            $form = $this->buildForm($new_content_form, $request);

            // Step 3 - Generate the files
            if ($form->isValid()) {
                if ($request->get('title_field')) {
                    $title_field = $this->dashesToCamelCase($request->get('title_field'));
                }
                else {
                    $title_field = 'id';
                }
                // Make files from templates
                $base_table = str_replace($qb->getTablePrefix(), '', $table);
                $fm = new FileMaker($config, $base_table, $form->getData('plural'), $form->getData('singular'), $title_field);
                $fm->setFileBasePath('src/AVCMS/Core/Bundle/BundleBuilder/BundleBlueprint/files');

                $fm->addFile('Model.php.bt', 'Model/{{model_class}}.php');

                if ($form->getData('admin_sections') == 1) {
                    $fm->addFile('AdminController.php.bt', 'Controller/{{cc_plural}}AdminController.php');
                    $fm->addFile('browser.twig.bt', 'resources/templates/{{plural}}_browser.twig');
                    $fm->addFile('edit_item.twig.bt', 'resources/templates/edit_{{singular}}.twig');
                    $fm->addFile('finder.twig.bt', 'resources/templates/{{plural}}_finder.twig');
                    $fm->addFile('content_routes.yml.bt', 'config/admin_routes.yml', FileMaker::APPEND);
                }

                $fm->processAndSaveFiles(true);

                // Make entity using CodeGenerator
                $entity_class = new PhpClass();
                $entity_class->setName($config->namespace.'\Model\\'.$fm->getVar('cc_singular'));
                $entity_class->setUseStatements(array('Entity' => 'AV\Model\Entity'));
                $entity_class->setParentClassName('Entity');
                $form_construct_body = '';

                $form_column_data = $form->getData('columns');

                foreach ($columns as $column_name => $column) {
                    $form_data = $form_column_data[$column_name];

                    if (isset($form_data['entity']) && $form_data['entity'] == 1) {
                        $setter = new PhpMethod($column['setter']);
                        $setter->setParameters(array(new PhpParameter('value')));
                        $setter->setBody('$this->set("'.$column_name.'", $value);');

                        $getter = new PhpMethod($column['getter']);
                        $getter->setBody('return $this->get("'.$column_name.'");');

                        $entity_class->setMethod($getter);
                        $entity_class->setMethod($setter);
                    }

                    if ($form_data['form_field'] !== 'none') {
                        $form_construct_body .= "\$this->add('$column_name', '$form_data[form_field]', array(
    'label' => '$form_data[form_label]',
));\r\n\r\n";
                    }
                }

                // Save Entity
                $gen = new DefaultGeneratorStrategy(new Visitor());
                $gen->setMethodSortFunc($this->orderMethodsClosure());

                $entity = $gen->generate($entity_class);
                file_put_contents($config->directory.'/Model/'.$fm->getVar('cc_singular').'.php', "<?php\n\n".$entity);

                if ($form->getData('admin_sections') == 1) {
                    // Admin Edit Form Class
                    $form_class = new PhpClass();
                    $form_class->setName($config->namespace.'\Form\\'.$fm->getVar('cc_singular').'AdminForm');
                    $form_class->setUseStatements(array('FormBlueprint' => 'AV\Form\FormBlueprint'));
                    $form_class->setParentClassName('FormBlueprint');

                    $construct = new PhpMethod('__construct');
                    $construct->setBody($form_construct_body);

                    $form_class->setMethod($construct);

                    // Admin Filters Form Class
                    $filters_form_class = new PhpClass();
                    $filters_form_class->setName($config->namespace.'\Form\\'.$fm->getVar('cc_plural').'AdminFiltersForm');
                    $filters_form_class->setUseStatements(array('AdminFiltersForm' => 'AVCMS\Bundles\Admin\Form\AdminFiltersForm'));
                    $filters_form_class->setParentClassName('AdminFiltersForm');

                    $filters_construct = new PhpMethod('__construct');
                    $filters_construct->setBody($form_construct_body);

                    $admin_form = $gen->generate($form_class);
                    $filters_form = $gen->generate($filters_form_class);

                    file_put_contents($config->directory.'/Form/'.$fm->getVar('cc_singular').'AdminForm.php', "<?php\n\n".$admin_form);
                    file_put_contents($config->directory.'/Form/'.$fm->getVar('cc_plural').'AdminFiltersForm.php', "<?php\n\n".$filters_form);
                }

                $content_types_file = $config->directory.'/config/generated_content.yml';

                $content_types = array();
                if (file_exists($content_types_file)) {
                    $content_types = Yaml::parse(file_get_contents($content_types_file));
                }

                $content_types[$form->getData('plural')] = $fm->getVars();

                file_put_contents($content_types_file, Yaml::dump($content_types));

                return new RedirectResponse($this->generateUrl('bundle_edit_content', array('bundle' => $bundle, 'content_name' => $form->getData('plural'))));
            }

            return new Response($this->render('@BundleManager/bundle_new_content_part2.twig', array('columns' => $columns, 'database_columns_form' => $form->createView(), 'bundle_config' => $config, 'is_ajax' => !$form->isSubmitted())));
        }
    }

    public function editContentAction(Request $request, $bundle, $content_name)
    {
        $config = $this->getBundleConfig($bundle);
        $qb = $this->container->get('query_builder');

        $gc_config_location = $config->directory.'/config/generated_content.yml';

        if (!file_exists($gc_config_location)) {
            throw $this->createNotFoundException(sprintf('Bundle %s not found', $bundle));
        }

        $content_types = Yaml::parse($gc_config_location);
        if (!isset($content_types[$content_name])) {
            throw $this->createNotFoundException(sprintf('Content type %s not found', $content_name));
        }

        $content = $content_types[$content_name];

        $entity_class = PhpClass::fromReflection(new \ReflectionClass($content['entity']), false);

        $admin_form = null;
        if (class_exists($content['admin_form'])) {
            $admin_form = new $content['admin_form'];
        }

        $table = preg_replace('/[^0-9a-zA-Z_]/', '', $content['database']);
        $columns_result = $qb->query("show columns from {$qb->getTablePrefix()}$table")->get(null, \PDO::FETCH_ASSOC);

        $columns = array();
        foreach($columns_result as $column) {
            $method_base = $this->dashesToCamelCase($column['Field'], true);

            $columns[$column['Field']] = array(
                'getter' => 'get'.$this->dashesToCamelCase($column['Field'], true),
                'setter' => 'set'.$this->dashesToCamelCase($column['Field'], true),
                'type' => $column['Type'],
                'entity' => ($entity_class->hasMethod('set'.$method_base) || $entity_class->hasMethod('get'.$method_base)),
                'form' => ($admin_form !== null && $admin_form->has($column['Field']))
            );
        }

        $new_content_form = new NewContentForm(false, $table);
        $new_content_form->addColumnsFields($columns);

        $form = $this->buildForm($new_content_form);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $form_column_data = $form->getData('columns');
            $form_construct_body = '';
            foreach ($columns as $column_name => $column) {
                if (isset($form_column_data[$column_name])) {
                    $form_data = $form_column_data[$column_name];

                    if (isset($form_data['entity']) && $form_data['entity'] == 1) {
                        $setter = new PhpMethod($column['setter']);
                        $setter->setParameters(array(new PhpParameter('value')));
                        $setter->setBody('$this->set("'.$column_name.'", $value);');

                        $getter = new PhpMethod($column['getter']);
                        $getter->setBody('return $this->get("'.$column_name.'");');

                        $entity_class->setMethod($getter);
                        $entity_class->setMethod($setter);
                    }

                    if ($form_data['form_field'] !== 'none') {
                        $form_construct_body .= "\$this->add('$column_name', '$form_data[form_field]', array(
    'label' => '$form_data[form_label]'
));\r\n\r\n";
                    }
                }
            }

            $gen = new DefaultGeneratorStrategy(new Visitor());
            $gen->setMethodSortFunc($this->orderMethodsClosure());

            file_put_contents($config->directory.'/Model/'.$content['cc_singular'].'.php', "<?php\n\n".$gen->generate($entity_class));

            if ($content['admin_form'] !== null && class_exists($content['admin_form']) && $form_construct_body) {
                $form_class = PhpClass::fromReflection(new \ReflectionClass($content['admin_form']), false);

                $construct = $form_class->getMethod('__construct');
                $body = $construct->getBody();

                $body .= $form_construct_body;
                $construct->setBody($body);

                $form_class->setMethod($construct);

                file_put_contents($config->directory.'/Form/'.$content['uc_singular'].'AdminForm.php', "<?php\n\n".$gen->generate($form_class));
            }
        }

        return new Response($this->render('@BundleManager/bundle_new_content_part2.twig', array(
            'columns' => $columns,
            'database_columns_form' => $form->createView(),
            'bundle_config' => $config,
            'editing_content' => true,
            'content' => $content
        )));
    }

    public function orderMethodsClosure() {
        return function(PhpMethod $a, PhpMethod $b) {
            if ($a->isStatic() !== $isStatic = $b->isStatic()) {
                return $isStatic ? 1 : -1;
            }

            if (($aV = $a->getVisibility()) !== $bV = $b->getVisibility()) {
                $aV = 'public' === $aV ? 3 : ('protected' === $aV ? 2 : 1);
                $bV = 'public' === $bV ? 3 : ('protected' === $bV ? 2 : 1);

                return $aV > $bV ? -1 : 1;
            }

            if ((strpos($a->getName(), 'get') !== false || strpos($a->getName(), 'set') !== false) && (strpos($b->getName(), 'get') === false && strpos($b->getName(), 'set') === false)) {
                return -1;
            }
            elseif ((strpos($a->getName(), 'get') === false && strpos($a->getName(), 'set') === false) && (strpos($b->getName(), 'get') !== false || strpos($b->getName(), 'set') !== false)) {
                return 1;
            }

            $aName = str_replace(array('set', 'get'), '', $a->getName());
            $bName = str_replace(array('set', 'get'), '', $b->getName());

            $rs = strcasecmp($aName, $bName);
            if (0 === $rs) {
                return 0;
            }

            return $rs > 0 ? 1 : -1;
        };
    }
}
