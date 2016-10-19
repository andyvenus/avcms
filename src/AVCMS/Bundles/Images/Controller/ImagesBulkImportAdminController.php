<?php
/**
 * User: Andy
 * Date: 09/06/15
 * Time: 15:51
 */

namespace AVCMS\Bundles\Images\Controller;

use AVCMS\Bundles\BulkUpload\Controller\BulkImportAdminController;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Images\Form\ImageAdminForm;
use AVCMS\Bundles\Images\Model\ImageCollections;
use AVCMS\Bundles\Images\Model\ImageFile;
use AVCMS\Bundles\Images\Model\ImageFiles;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImagesBulkImportAdminController extends BulkImportAdminController
{
    /**
     * @var ImageCollections
     */
    private $imageCollections;

    /**
     * @var ImageFiles
     */
    private $imageFiles;

    public function setUp()
    {
        $this->contentType = 'images';
        $this->mainDir = $this->getParam('images_dir');
        $this->imageCollections = $this->model('ImageCollections');
        $this->imageFiles = $this->model('ImageFiles');
    }

    public function importAction(Request $request)
    {
        $folder = (string) str_replace(['.', '@'], ['', '/'], $request->get('folder'));
        if ($folder === '') {
            throw $this->createNotFoundException("No folder specified");
        }

        $entity = $this->imageCollections->newEntity();

        $formBlueprint = new ImageAdminForm($entity, new CategoryChoicesProvider($this->model('ImageCategories')), true);
        $formBlueprint->setSuccessMessage('Images Imported');
        $form = $this->buildForm($formBlueprint);
        $form->setData('name', '{clean_filename}');

        $contentHelper = $this->editContentHelper($this->imageCollections, $form, $entity);
        $contentHelper->handleRequest($request);

        $itr = new \DirectoryIterator($this->mainDir.'/'.$folder);

        if ($contentHelper->formSubmitted()) {
            if ($contentHelper->formValid()) {
                $form->saveToEntities();

                $originalTags = $contentHelper->getForm()->getData('tags');
                $imagesImported = 0;

                if ($request->request->get('import_type') == 'folders') {
                    $doFolders = true;
                }

                foreach ($itr as $file) {
                    $files = [];

                    if ((!$file->isFile() && !isset($doFolders)) || ($file->isFile() && isset($doFolders)) || $file->isDot()) {
                        continue;
                    }

                    if ($file->isDir()) {
                        $collectionFolderItr = new \DirectoryIterator($this->mainDir.'/'.$folder.'/'.$file->getFilename());
                        foreach ($collectionFolderItr as $collectionFile) {
                            if (!$collectionFile->isFile()) {
                                continue;
                            }

                            $files[] = $this->fileToArray($collectionFile);
                        }
                        $parentFolderName = $file->getFilename();

                        $folderImported = $this->imageCollections->query()->where('import_folder', $folder.'/'.$parentFolderName)->count();
                        if ($folderImported) {
                            continue;
                        }
                    }
                    else {
                        $files[] = $this->fileToArray($file);
                        $parentFolderName = null;
                    }

                    if (empty($files)) {
                        continue;
                    }

                    $dimensions = @getimagesize($files[0]['path'].'/'.$files[0]['filename']);
                    if (!is_array($dimensions)) {
                        continue;
                    }

                    if ($this->imageFiles->query()->where('url', $folder.'/'.$files[0]['filename'])->count() > 0) {
                        continue;
                    }

                    $newImageCollection = clone $entity;
                    $contentHelper->setEntity($newImageCollection);

                    $replaceValues = [];
                    $replaceValues['{filename}'] = str_replace('.'.$files[0]['extension'], '', $files[0]['basename']);
                    $replaceValues['{clean_filename}'] = ucwords(str_replace(['_', '-'], ' ', str_replace('.'.$files[0]['extension'], '', $files[0]['basename'])));
                    $replaceValues['{folder_name}'] = basename($folder);
                    $replaceValues['{original_width}'] = $dimensions[0];
                    $replaceValues['{original_height}'] = $dimensions[1];
                    $replaceValues['{filename_words_comma}'] = strtolower(str_replace(' ', ',', $replaceValues['{clean_filename}']));

                    $allData = $newImageCollection->toArray();
                    $newData = [];

                    foreach ($allData as $key => $value) {
                        if (!is_array($value)) {
                            $newData[$key] = str_replace(array_keys($replaceValues), $replaceValues, $value);
                        }
                    }

                    $newTags = str_replace(array_keys($replaceValues), $replaceValues, $originalTags);
                    $contentHelper->getForm()->setData('tags', $newTags);

                    $newImageCollection->fromArray($newData);

                    $slug = $this->container->get('slug.generator')->slugify($newImageCollection->getName());
                    if ($this->imageCollections->query()->where('slug', $slug)->count() > 0) {
                        $slug .= '-'.time().$imagesImported;
                    }

                    $newImageCollection->setSlug($slug);

                    if (isset($doFolders)) {
                        $newImageCollection->setImportFolder($folder.'/'.$parentFolderName);
                    }

                    $contentHelper->save(false);

                    $imageFiles = [];
                    foreach ($files as $imgFile) {
                        $imageFile = $this->imageFiles->newEntity();

                        $imageUrl = $folder.'/';

                        if ($parentFolderName !== null) {
                            $imageUrl .= $parentFolderName.'/';
                        }

                        $imageUrl .= $imgFile['filename'];

                        $imageFile->setUrl($imageUrl);
                        $imageFile->setCollectionId($newImageCollection->getId());
                        $imageFile->setImportFolder($folder);

                        $imageFiles[] = $imageFile;
                        $this->imageFiles->save($imageFile);
                    }

                    $extension = pathinfo($imageFiles[0]->getUrl())['extension'];
                    $newImageCollection->setThumbnail($imageFiles[0]->getId().'.'.$extension);
                    $newImageCollection->setTotalImages(1);
                    $contentHelper->save(false);

                    $imagesImported++;
                }

                $formBlueprint->setSuccessMessage("$imagesImported ".$this->trans("image collections imported"));
            }

            return new JsonResponse(['form' => $form->createView()->getJsonResponseData()]);
        }

        $totalFiles = 0;
        $totalSubdirectories = 0;
        foreach ($itr as $file) {
            if ($file->isFile() && in_array($file->getExtension(), ['gif', 'bmp', 'jpeg', 'jpg', 'png'])) {
                $totalFiles++;
            }
            elseif ($file->isDir() && !$file->isDot()) {
                $subDirItr = new \DirectoryIterator($file->getPathname());
                foreach ($subDirItr as $subDirFile) {
                    if ($subDirFile->isFile()) {
                        $totalSubdirectories++;
                        break;
                    }
                }
            }
        }

        $importedFiles = $this->imageFiles->query()->where('import_folder', $folder)->count();
        $importedSubDirs = $this->imageCollections->query()->where('import_folder', 'LIKE', $folder.'/%')->count();

        return new Response($this->renderAdminSection('@Images/admin/images_bulk_import.twig', [
            'item' => ['id' => $folder],
            'folder' => $folder,
            'form' => $form->createView(),
            'total_files' => $totalFiles,
            'imported_files' => $importedFiles,
            'total_subdirectories' => $totalSubdirectories,
            'imported_subdirectories' => $importedSubDirs
        ]));
    }

    protected function fileToArray(\SplFileInfo $file)
    {
        return [
            'basename' => $file->getBasename(),
            'filename' => $file->getFilename(),
            'extension' => $file->getExtension(),
            'path' => $file->getPath()
        ];
    }
}
