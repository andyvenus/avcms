<?php

namespace AVCMS\Bundles\BulkUpload\Controller;

use AV\Form\FormBlueprint;
use AV\Form\FormError;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\BulkUpload\Form\BulkUploadForm;
use AVCMS\Bundles\BulkUpload\Form\RecursiveDirectoryChoicesProvider;
use AVCMS\Bundles\BulkUpload\Form\BulkImportAdminFiltersForm;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BulkImportAdminController extends AdminBaseController
{
    protected $browserTemplate = '@BulkUpload/admin/bulk_import_browser.twig';

    protected $mainDir;

    protected $contentType;

    public function setUp()
    {
        $this->contentType = 'wallpapers';
        $this->mainDir = $this->bundle->config->wallpapers_dir;
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@BulkUpload/admin/bulk_import_browser.twig');
    }

    public function folderAction(Request $request)
    {
        $folder = (string) str_replace(['.', '@'], ['', '/'], $request->get('folder'));
        if ($folder === '') {
            throw $this->createNotFoundException("No folder specified");
        }

        $files = [];
        $itr = new \DirectoryIterator($this->mainDir.'/'.$folder);
        foreach ($itr as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $files[] = ['filename' => $file->getFilename(), 'image_path' => $this->mainDir.'/'.$folder.'/'.$file->getFilename()];
        }

        return new Response($this->renderAdminSection('@BulkUpload/admin/folder_contents.twig', ['files' => $files]));
    }

    public function finderAction(Request $request)
    {
        $showSingleCharDirs = $request->get('show_single_char_dirs', false);

        $dirs = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->mainDir),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        $items = [];

        if ($request->get('page') == 1) {
            foreach ($dirs as $file) {
                if (in_array($file->getBasename(), array('.', '..'))) {
                    continue;
                } elseif ($file->isDir() && ($showSingleCharDirs == 1 || strlen($file->getFilename()) > 1)) {
                    $filename = str_replace($this->mainDir.'/', '', $file->getPath().'/'.$file->getFilename());
                    if (!$request->get('search') || strpos($filename, $request->get('search')) !== false) {
                        $id = str_replace('/', '@', $filename);

                        $fileItr = new \DirectoryIterator($this->mainDir.'/'.$filename);
                        $totalFiles = $totalFolders = 0;
                        foreach ($fileItr as $innerFile) {
                            if ($innerFile->isFile() && in_array($innerFile->getExtension(), ['gif', 'bmp', 'jpeg', 'jpg', 'png'])) {
                                $totalFiles++;
                            }
                            elseif ($innerFile->isDir() && !$innerFile->isDot()) {
                                $totalFolders++;
                            }
                        }
                        $importedFiles = 'todo';//$this->model(ucfirst($this->contentType))->query()->where('file', 'LIKE', $filename.'%')->count();

                        $items[] = [
                            'id' => $id,
                            'name' => $filename,
                            'imported_files' => $importedFiles,
                            'total_files' => $totalFiles,
                            'total_folders' => $totalFolders,
                            'dir' => $file
                        ];
                    }
                }
            }
        }

        return new Response($this->render('@BulkUpload/admin/bulk_import_finder.twig', array_merge(array('items' => $items, 'page' => 1), $this->getSharedTemplateVars())));
    }

    public function bulkUploadAction(Request $request)
    {
        $blueprint = new BulkUploadForm(new RecursiveDirectoryChoicesProvider($this->getParam('root_dir').'/'.$this->mainDir, false));
        $blueprint->setAction($this->generateUrl($this->contentType.'_admin_upload'));
        $form = $this->buildForm($blueprint, $request);

        return new Response($this->renderAdminSection('@BulkUpload/admin/bulk_upload.twig', ['form' => $form->createView()]));
    }

    public function addFolderAction(Request $request)
    {
        $formBp = new FormBlueprint();
        $formBp->setName('bulk_upload_new_folder');
        $formBp->setAction($this->generateUrl($this->contentType.'_bulk_import_new_folder'));
        $formBp->add('folder_name', 'text', [
            'label' => 'Folder',
            'help' => 'The new folder directory, use forward slashes to create a sub-directory'
        ]);

        $form = $this->buildForm($formBp, $request);

        if ($form->isSubmitted()) {

            $newDir = $this->mainDir.'/'.$form->getData('folder_name');

            if (file_exists($newDir)) {
                $form->addCustomErrors([new FormError('folder_name', 'Folder already exists')]);
            }
            else {
                mkdir($newDir, 0777, true);
            }

            return new JsonResponse(['success' => $form->isValid(), 'form' => $form->createView()->getJsonResponseData(), 'folder' => $form->getData('folder_name')]);
        }

        return new JsonResponse(['html' => $this->render('@CmsFoundation/modal_form.twig', ['form' => $form->createView(), 'modal_title' => 'Add Folder'])]);
    }

    public function deleteFolderAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $folder = str_replace(['.', '@'], ['', '/'], $request->request->get('ids'));
        $fullPath = $this->getParam('root_dir').'/'.$this->mainDir.'/'.$folder;
        if (!$folder || !file_exists($fullPath)) {
            return new JsonResponse(['success' => 0, 'error' => $this->trans('Folder cannot be deleted because it doesn\'t exist')]);
        }

        if (count(glob($fullPath."/*")) === 0) {
            rmdir($fullPath);
            return new JsonResponse(['success' => 1]);
        }
        else {
            return new JsonResponse(['success' => 0, 'error' => $this->trans('Folder not empty')]);
        }
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $fbp = new BulkImportAdminFiltersForm();
        $fbp->remove('order');

        $templateVars['finder_filters_form'] = $this->buildForm($fbp)->createView();

        $templateVars['content_type'] = $this->contentType;

        return $templateVars;
    }
}
