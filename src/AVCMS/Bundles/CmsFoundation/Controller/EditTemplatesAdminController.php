<?php
/**
 * User: Andy
 * Date: 15/02/15
 * Time: 12:29
 */

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AV\Cache\CacheClearer;
use AV\Form\FormBlueprint;
use AV\Kernel\Bundle\Exception\NotFoundException;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\CmsFoundation\Form\EditTemplatesAdminFiltersForm;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EditTemplatesAdminController extends AdminBaseController
{
    protected $browserTemplate = '@CmsFoundation/admin/edit_templates_browser.twig';

    public function setUp()
    {
        if (!$this->isGranted('ADMIN_EDIT_TEMPLATES')) {
            throw new AccessDeniedException;
        }
    }

    public function homeAction(Request $request)
    {
        return $this->handleManage($request, $this->browserTemplate);
    }

    public function finderAction(Request $request)
    {
        $templates = [];

        if ($request->query->get('bundle')) {
            $info = $this->getFilesInfo($request->get('bundle'));

            $dirs = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($info['resources_dir']),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            if ($request->get('page') == 1) {
                foreach ($dirs as $file) {
                    if ($file->isDir()) {
                        continue;
                    } elseif (in_array($file->getExtension(), ['twig', 'css'])) {
                        $filePath = str_replace($info['resources_dir'], '', $file->getPath().'/'.$file->getFilename());

                        if ($request->get('search') && strpos($filePath, $request->get('search')) === false) {
                            continue;
                        }

                        if (!$request->get('admin_templates', false) && strpos($filePath, 'admin') !== false) {
                            continue;
                        }

                        $fileType = $this->getFileType($file->getFilename());

                        $id = $filePath;
                        if ($info['type'] != 'template') {
                            $id = str_replace('/'.$fileType.'/', '', $filePath);
                        }

                        $id = str_replace('/', '::', $id);
                        if ($id[0] == ':') {
                            $id = ltrim($id, '::');
                        }

                        $hierarchy = $this->getFileHierarchy($info['type'], $request->get('bundle'), $fileType, $filePath);
                        reset($hierarchy);
                        $currentTemplateLocation = key($hierarchy);

                        $templates[] = [
                            'id' => $id,
                            'file' => $id,
                            'name' => basename($filePath),
                            'dir' => $file,
                            'hierarchy' => $hierarchy,
                            'current_template_location' => $currentTemplateLocation,
                            'bundle' => $request->get('bundle')
                        ];
                    }
                }
            }
        }

        return new Response($this->render('@CmsFoundation/admin/edit_templates_finder.twig', ['items' => $templates, 'page' => 1]));
    }

    public function editAction(Request $request)
    {
        $fileType = $this->getFileType($request->get('file'));
        $filename = str_replace('::', '/', $request->get('file'));

        $filesInfo = $this->getFilesInfo($request->get('bundle'));

        $hierarchy = $this->getFileHierarchy($filesInfo['type'], $request->get('bundle'), $fileType, $filename);

        reset($hierarchy);
        $currentTemplateLocation = key($hierarchy);

        $fileContents = file_get_contents($hierarchy[$currentTemplateLocation].'/'.$filename);

        $formBlueprint = new FormBlueprint();
        $formBlueprint->setSuccessMessage('Template Saved');
        $formBlueprint->setName('edit-template-form');
        $formBlueprint->add('template', 'textarea', [
            'label' => 'Template',
            'default' => $fileContents,
            'attr' => ['class' => 'codemirror', 'id' => 'codemirror-'.$request->get('bundle').'-'.$request->get('file')]
        ]);

        $form = $this->buildForm($formBlueprint, $request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $basename = basename($filename);
                $resourceDir = str_replace($basename, '', $filename);
                $dir = $this->getParam('root_dir').'/webmaster/resources/';

                if ($filesInfo['type'] == 'bundle') {
                    $dir .= $request->get('bundle') . '/' . $fileType . '/' . $resourceDir;
                }
                else {
                    $dir .= 'templates/frontend/'.$request->get('bundle');
                }

                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                file_put_contents($dir.'/'.$basename, $form->getData('template'));

                if ($fileType === 'templates') {
                    $cacheBuster = new CacheClearer($this->getParam('cache_dir').'/twig');
                    $cacheBuster->clearCaches();
                }

                return new JsonResponse(['success' => true, 'id' => $request->get('file'), 'form' => $form->createView()->getJsonResponseData()]);
            }
        }

        return new Response($this->renderAdminSection('@CmsFoundation/admin/edit_template.twig', [
            'item' => [
                'id' => $request->get('file'),
                'bundle' => $request->get('bundle'),
                'file' => $filename,
                'current_location' => $currentTemplateLocation
            ],
            'form' => $form->createView(),
        ]));
    }

    public function resetTemplateAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $file = str_replace('::', '/', $request->get('file'));
        $filesInfo = $this->getFilesInfo($request->get('bundle'));

        $dir = $this->getParam('root_dir').'/webmaster/resources/';

        if ($filesInfo['type'] == 'bundle') {
            $fileType = $this->getFileType($file);
            $dir .= $request->get('bundle') . '/' . $fileType;
        }
        else {
            $dir .= 'templates/frontend/'.$request->get('bundle');
        }

        unlink($dir.'/'.$file);

        return new JsonResponse(['success' => true]);
    }


    protected function getFilesInfo($name)
    {
        try {
            $bundleConfig = $this->get('bundle_manager')->getBundleConfig($name);
            return ['type' => 'bundle', 'resources_dir' => $bundleConfig->directory.'/resources'];
        } catch (NotFoundException $e) {

        }

        // Template
        if (!isset($resourcesDir)) {
            $resourcesDir = $this->getParam('root_dir').'/webmaster/templates/frontend/'.$name;

            if (!file_exists($resourcesDir)) {
                throw $this->createNotFoundException();
            }

            return ['type' => 'template', 'resources_dir' => $resourcesDir];
        }
    }

    protected function getFileHierarchy($type, $bundle, $fileType, $filePath)
    {
        if ($type === 'bundle') {
            $hierarchy = $this->get('bundle.resource_locator')->findFileHierarchy($bundle, str_replace('/' . $fileType, '', $filePath), $fileType);
        }
        else {
            $hierarchy = [];

            $webmasterLocation = $this->getParam('root_dir').'/webmaster/resources/templates/frontend/'.$bundle;
            if (file_exists($webmasterLocation.'/'.$filePath)) {
                $hierarchy['webmaster'] = $webmasterLocation;
            }
            $hierarchy['template'] = $this->getParam('root_dir').'/webmaster/templates/frontend/'.$bundle;
        }

        return $hierarchy;
    }

    protected function getFileType($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if ($ext === 'js') {
            return 'javascript';
        }
        elseif ($ext === 'css') {
            return 'css';
        }

        return 'templates';
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $bundles = $this->get('bundle_manager')->getBundleConfigs();

        $template = basename($this->get('template_manager')->getCurrentTemplate());

        $bundleChoices = [0 => $this->trans('Bundle or template...'), $template => $this->trans('Current Template').': '.$template];

        foreach ($bundles as $bundle) {
            if (file_exists($bundle->directory . '/resources/templates')) {
                $bundleChoices[$bundle->name] = 'Bundle: '.$bundle->name;
            }
        }

        $templateDirs = new \DirectoryIterator($this->getParam('root_dir').'/webmaster/templates/frontend');
        foreach ($templateDirs as $dir) {
            if (!$dir->isDot() && file_exists($dir->getPathname().'/template.yml') && !isset($bundleChoices[$dir->getFilename()])) {
                $bundleChoices[$dir->getFilename()] = 'Template: '.$dir->getFilename();
            }
        }

        $fbp = new EditTemplatesAdminFiltersForm($bundleChoices);

        $templateVars['finder_filters_form'] = $this->buildForm($fbp)->createView();

        return $templateVars;
    }
}
