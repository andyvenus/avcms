<?php

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Bundles\BulkUpload\Controller\BulkImportAdminController;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Wallpapers\Form\WallpaperAdminForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WallpapersBulkImportAdminController extends BulkImportAdminController
{
    /**
     * @var \AVCMS\Bundles\Wallpapers\Model\Wallpapers
     */
    protected $wallpapers;

    public function setUp()
    {
        $this->wallpapers = $this->model('Wallpapers');

        $this->contentType = 'wallpapers';
        $this->mainDir = $this->bundle->config->wallpapers_dir;
    }

    public function importAction(Request $request)
    {
        $folder = (string) str_replace(['.', '@'], ['', '/'], $request->get('folder'));
        if ($folder === '') {
            throw $this->createNotFoundException("No folder specified");
        }

        $entity = $this->wallpapers->newEntity();

        $formBlueprint = new WallpaperAdminForm(0, new CategoryChoicesProvider($this->model('WallpaperCategories')), true);
        $formBlueprint->remove('slug');
        $formBlueprint->setSuccessMessage('Wallpapers Imported');
        $form = $this->buildForm($formBlueprint);
        $form->setData('name', '{clean_filename}');

        $contentHelper = $this->editContentHelper($this->wallpapers, $form, $entity);
        $contentHelper->handleRequest($request);

        $itr = new \DirectoryIterator($this->bundle->config->wallpapers_dir.'/'.$folder);

        if ($contentHelper->formSubmitted()) {
            if ($contentHelper->formValid()) {
                $form->saveToEntities();

                $originalTags = $contentHelper->getForm()->getData('tags');
                $wallpapersImported = 0;

                foreach ($itr as $file) {
                    if (!$file->isFile()) {
                        continue;
                    }

                    $dimensions = @getimagesize($file->getPath().'/'.$file->getFilename());
                    if (!is_array($dimensions)) {
                        continue;
                    }

                    if ($this->wallpapers->query()->where('file', $folder.'/'.$file->getFilename())->count() > 0) {
                        continue;
                    }

                    $newWallpaper = clone $entity;
                    $contentHelper->setEntity($newWallpaper);
                    $newWallpaper->setFile($folder.'/'.$file->getFilename());

                    $replaceValues = [];
                    $replaceValues['{filename}'] = str_replace('.'.$file->getExtension(), '', $file->getBasename());
                    $replaceValues['{clean_filename}'] = ucwords(str_replace(['_', '-'], ' ', str_replace('.'.$file->getExtension(), '', $file->getBasename())));
                    $replaceValues['{folder_name}'] = basename($folder);
                    $replaceValues['{original_width}'] = $dimensions[0];
                    $replaceValues['{original_height}'] = $dimensions[1];

                    $allData = $newWallpaper->toArray();
                    $newData = [];

                    foreach ($allData as $key => $value) {
                        if (!is_array($value)) {
                            $newData[$key] = str_replace(array_keys($replaceValues), $replaceValues, $value);
                        }
                    }

                    $newTags = str_replace(array_keys($replaceValues), $replaceValues, $originalTags);
                    $contentHelper->getForm()->setData('tags', $newTags);

                    $newWallpaper->fromArray($newData);

                    $slug = $this->container->get('slug.generator')->slugify($newWallpaper->getName());
                    if ($this->wallpapers->query()->where('slug', $slug)->count() > 0) {
                        $slug .= '-'.time().$wallpapersImported;
                    }

                    $newWallpaper->setSlug($slug);
                    $newWallpaper->setOriginalWidth($dimensions[0]);
                    $newWallpaper->setOriginalHeight($dimensions[1]);

                    $contentHelper->save(false);
                    $wallpapersImported++;
                }

                $formBlueprint->setSuccessMessage("$wallpapersImported ".$this->trans("wallpapers imported"));
            }

            return new JsonResponse(['form' => $form->createView()->getJsonResponseData()]);
        }

        $totalFiles = 0;
        foreach ($itr as $file) {
            if ($file->isFile() && in_array($file->getExtension(), ['gif', 'bmp', 'jpeg', 'jpg', 'png'])) {
                $totalFiles++;
            }
        }

        $importedFiles = $this->wallpapers->query()->where('file', 'LIKE', $folder.'%')->count();

        return new Response($this->renderAdminSection('@Wallpapers/admin/wallpaper_bulk_import.twig', [
            'item' => ['id' => $folder],
            'folder' => $folder,
            'form' => $form->createView(),
            'total_files' => $totalFiles,
            'imported_files' => $importedFiles
        ]));
    }
}
