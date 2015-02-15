<?php
/**
 * User: Andy
 * Date: 15/02/15
 * Time: 12:29
 */

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\CmsFoundation\Form\EditTemplatesFiltersForm;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EditTemplatesAdminController extends AdminBaseController
{
    protected $browserTemplate = '@CmsFoundation/admin/edit_templates_browser.twig';

    public function homeAction(Request $request)
    {
        return $this->handleManage($request, $this->browserTemplate);
    }

    public function finderAction(Request $request)
    {
        $templates = [];

        if ($request->query->has('bundle')) {
            $bundleConfig = $this->get('bundle_manager')->getBundleConfig($request->get('bundle', 'CmsFoundation'));

            $dirs = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($bundleConfig->directory.'/resources'),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            if ($request->get('page') == 1) {
                foreach ($dirs as $file) {
                    if ($file->isDir()) {
                        continue;
                    } elseif (in_array($file->getExtension(), ['twig', 'css'])) {
                        $filePath = str_replace($bundleConfig->directory.'/resources', '', $file->getPath().'/'.$file->getFilename());
                        if ((!$request->get('search') || strpos($filePath, $request->get('search')) !== false) && ($request->get('admin_templates', false) || strpos($filePath, 'admin') === false)) {

                            if ($file->getExtension() == 'twig') {
                                $type = 'templates';
                            }
                            else {
                                $type = 'css';
                            }

                            $id = str_replace('/', '::', str_replace('/'.$type.'/', '', $filePath));

                            $hierarchy = $this->get('bundle.resource_locator')->findFileHierarchy($bundleConfig->name, str_replace('/'.$type, '', $filePath), $type);
                            reset($hierarchy);
                            $currentTemplateLocation = key($hierarchy);

                            $templates[] = [
                                'id' => $id,
                                'file' => $id,
                                'name' => basename($filePath),
                                'dir' => $file,
                                'hierarchy' => $hierarchy,
                                'current_template_location' => $currentTemplateLocation,
                                'bundle' => $bundleConfig->name
                            ];
                        }
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

        $filePath = $this->get('bundle.resource_locator')->findFileDirectory($request->get('bundle'), $filename, $fileType);

        $hierarchy = $this->get('bundle.resource_locator')->findFileHierarchy($request->get('bundle'), str_replace('/'.$fileType, '', $filename), $fileType);
        reset($hierarchy);
        $currentTemplateLocation = key($hierarchy);
        $fileContents = file_get_contents($filePath);

        $formBlueprint = new FormBlueprint();
        $formBlueprint->setSuccessMessage('Template Saved');
        $formBlueprint->add('template', 'textarea', [
            'label' => 'Template',
            'default' => $fileContents,
            'attr' => ['class' => 'codemirror', 'id' => 'codemirror-'.$request->get('file')]
        ]);

        $form = $this->buildForm($formBlueprint, $request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $basename = basename($filename);
                $resourceDir = str_replace($basename, '', $filename);
                $dir = $this->getParam('root_dir').'/webmaster/resources/'.$request->get('bundle').'/'.$fileType.$resourceDir;

                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                file_put_contents($dir.'/'.$filename, $form->getData('template'));

                return new JsonResponse(['success' => true, 'form' => $form->createView()->getJsonResponseData()]);
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

        $bundleChoices = [];

        foreach ($bundles as $bundle) {
            if (file_exists($bundle->directory . '/resources/templates')) {
                $bundleChoices[$bundle->name] = $bundle->name;
            }
        }

        $fbp = new EditTemplatesFiltersForm($bundleChoices);

        $templateVars['finder_filters_form'] = $this->buildForm($fbp)->createView();

        return $templateVars;
    }
}
