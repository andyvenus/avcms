<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 20:26
 */

namespace AVCMS\Bundles\Images\Controller;

use AV\FileHandler\UploadedFileHandler;
use AV\Form\FormError;
use AV\Validation\Rules\SymfonyFile;
use AV\Validation\Rules\SymfonyImageFile;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Images\Form\ImageFrontendFiltersForm;
use AVCMS\Bundles\Images\Form\SubmitImageForm;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Rss\RssFeed;
use AVCMS\Core\Rss\RssItem;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Util\SecureRandom;
use ZipArchive;

class ImagesController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Images\Model\ImageCollections
     */
    private $imageCollections;

    private $imageCategories;

    /**
     * @var \AVCMS\Bundles\Images\Model\ImageFiles
     */
    private $imageFiles;

    public function setUp() {
        $this->imageCollections = $this->model('ImageCollections');
        $this->imageFiles = $this->model('ImageFiles');
        $this->imageCategories = $this->model('ImageCategories');
    }

    public function imageCollectionAction(Request $request, $slug)
    {
        $imageCollection = $this->imageCollections->find()
            ->published()
            ->slug($slug)
            ->join($this->imageCategories, ['name', 'slug'])
            ->joinTaxonomy('tags')
            ->first();

        if (!$imageCollection) {
            throw $this->createNotFoundException();
        }

        $imageCollection->files = $this->imageFiles->getImageFiles($imageCollection->getId());

        if ($imageCollection->getSubmitterId()) {
            $imageCollection->submitter = $this->model('@users')->getOne($imageCollection->getSubmitterId());
        }

        if (!$imageCollection) {
            throw $this->createNotFoundException('Image Not Found');
        }

        $this->container->get('hitcounter')->registerHit($this->imageCollections, $imageCollection->getId(), 'hits', 'id', 'last_hit');

        return new Response($this->render('@Images/image_collection.twig', ['image_collection' => $imageCollection]));
    }

    public function downloadCollectionAction($slug)
    {
        $collection = $this->imageCollections->find()->slug($slug)->first();

        if (!$collection) {
            throw $this->createNotFoundException();
        }

        $defaultDownloadableOff = $collection->getDownloadable() == 'default' && $this->setting('images_default_download') == 0;
        if ($defaultDownloadableOff && $collection->getDownloadable() == 0) {
            throw $this->createNotFoundException('Collection not downloadable');
        }

        $imageFiles = $this->imageFiles->getImageFiles($collection->getId());

        $headers = [];

        if (count($imageFiles) === 1) {
            $imageFile = reset($imageFiles);

            $pathInfo = pathinfo($imageFile->getUrl());
            $headers['Content-Disposition'] = 'attachment; filename="'.$pathInfo['basename'].'"';

            $image = $this->getParam('root_dir').'/'.$this->getParam('images_dir').'/'.$imageFile->getUrl();

            $imageManager = new ImageManager(['driver' => $this->setting('images_driver')]);
            $thumbnail = $imageManager->make($image);

            return new Response($thumbnail->encode(), 200, $headers);
        }

        $zipsDir = $this->getParam('cache_dir').'/image-zips/'.$collection->getId();

        if (!is_dir($zipsDir)) {
            mkdir($zipsDir, 0777, true);
        }

        $zipPath = $zipsDir.'/'.$collection->getSlug().'.zip';

        if (!file_exists($zipPath)) {
            $zip = new \ZipArchive();

            if (($err = $zip->open($zipPath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)) !== true) {
                throw new \Exception('Cannot create zip: error '.$err);
            }

            foreach ($imageFiles as $file) {
                $filePath = $this->getParam('images_dir') . '/' . $file->getUrl();

                $zip->addFile($filePath, pathinfo($filePath, PATHINFO_BASENAME));
            }

            $zip->close();
        }

        $headers['Content-Disposition'] = 'attachment; filename="'.$collection->getSlug().'.zip"';

        return new Response(file_get_contents($zipPath), 200, $headers);
    }

    public function browseImagesAction(Request $request, $pageType = 'archive')
    {
        $finder = $this->imageCollections->find();
        $query = $finder->published()
            ->setResultsPerPage($this->setting('browse_images_per_page'))
            ->setSearchFields(['image_collections.name'])
            ->handleRequest($request, [
                'page' => 1,
                'order' => 'publish-date-newest',
                'tags' => null,
                'search' => null,
                'mobile_only' => false
            ]);

        if ($this->setting('show_image_category')) {
            $query->join($this->imageCategories, ['name', 'slug']);
        }

        $category = null;
        if ($request->get('category') !== null) {
            $category = $this->imageCategories->getFullCategory($request->get('category'));

            if ($category) {
                $query->category($category);
            }
            else {
                throw $this->createNotFoundException('Category Not Found');
            }
        }

        if ($pageType == 'likes') {
            if ($request->get('likes_user') === null) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new AccessDeniedException('You must be logged in to view your liked images');
                }
            }
            else {
                $user = $this->model('Users')->find()->slug($request->get('likes_user'))->first();

                if (!$user) {
                    throw $this->createNotFoundException();
                }
            }

            $ratings = $this->model('LikeDislike:Ratings');
            $ids = $ratings->getLikedIds($user->getId(), 'image');
            $query = $query->ids($ids, 'image_collections.id');
        }

        if ($pageType === 'featured') {
            $query->featured();
        }

        $images = $query->get();

        $formBp = new ImageFrontendFiltersForm();
        $attr = $request->attributes->all();
        $attr['page'] = 1;
        $formBp->setAction($this->generateUrl($request->attributes->get('_route'), $attr));

        $filtersForm = $this->buildForm($formBp, $request);

        return new Response($this->render('@Images/browse_images.twig', array(
            'images' => $images,
            'total_pages' => $finder->getTotalPages(),
            'current_page' => $finder->getCurrentPage(),
            'page_type' => $pageType,
            'category' => $category,
            'filters_form' => $filtersForm->createView(),
            'finder_request' => $finder->getRequestFilters(),
            'admin_settings' => $this->get('settings_manager'),
            'likes_user' => isset($user) ? $user : null,
        )));
    }

    public function submitImageAction(Request $request)
    {
        if (!$this->isGranted(['PERM_SUBMIT_IMAGES'])) {
            if (!$this->isGranted(['IS_AUTHENTICATED_FULLY', 'IS_AUTHENTICATED_REMEMBERED'])) {
                return $this->redirect('login', [], 302, 'info', $this->trans('Please login to submit a image'));
            }
            else {
                throw new AccessDeniedException;
            }
        }

        if (!$this->setting('images_allow_uploads')) {
            throw $this->createNotFoundException();
        }

        $submissions = $this->model('ImageSubmissions');

        $pendingCount = $submissions->query()->where('submitter_id', $this->activeUser()->getId())->count();
        if ($pendingCount >= $this->setting('images_max_submissions')) {
            return $this->redirect('home', [], 302, 'info', $this->trans('You have already submitted {count} images, please wait for them to be accepted first', ['count' => $this->setting('images_max_submissions')]));
        }

        $newSubmission = $submissions->newEntity();

        $formBlueprint = new SubmitImageForm(new CategoryChoicesProvider($this->model('ImageCategories')));

        $form = $this->buildForm($formBlueprint, $request, $newSubmission);

        if ($form->isValid()) {
            $form->saveToEntities();

            $fileHandler = new UploadedFileHandler();

            $submissionsImagesDir = $this->getParam('image_submissions_dir');
            if (!file_exists($submissionsImagesDir)) {
                mkdir($submissionsImagesDir, 0777, true);
            }

            $imageValidator = new SymfonyImageFile($this->setting('images_submission_width_limit'), $this->setting('images_submission_height_limit'));

            $fileSizeLimit = $this->setting('images_submission_filesize') * 1000000;
            $fileValidator = new SymfonyFile($fileSizeLimit, UploadedFileHandler::getImageFiletypes());

            $files = $request->files->get('files');

            if (!is_array($files)) {
                $files = [$files];
            }

            $imageFiles = [];
            $secureRandom = bin2hex((new SecureRandom())->nextBytes(5));

            foreach ($files as $uploadedImage) {
                if (!$fileValidator->assert($uploadedImage)) {
                    $form->addCustomErrors([new FormError(
                        'files',
                        'The image {filename} is not valid. Make sure it is under {filesize_limit} MB and is a png, gif, bmp, jpeg or other image file',
                        true,
                        ['filename' => $uploadedImage->getClientOriginalName(), 'filesize_limit' => $this->setting('images_submission_filesize')]
                    )]);

                    continue;
                }

                if (!$imageValidator->assert($uploadedImage)) {
                    $form->addCustomErrors([new FormError(
                        'files',
                        'The image {filename} is larger than the maximum resolution of {width}x{height}',
                        true,
                        array_merge($imageValidator->getRuleData(), ['filename' => $uploadedImage->getClientOriginalName()])
                    )]);

                    continue;
                }

                $imageFile = $this->imageFiles->newEntity();

                $fullFilePath = $fileHandler->moveFile($uploadedImage, $submissionsImagesDir.'/'.$secureRandom);

                $fileRelPath = str_replace($this->getParam('images_dir') . '/', '', $fullFilePath);

                $imageFile->setUrl($fileRelPath);

                $imageFiles[] = $imageFile;
            }

            if ($form->isValid() && empty($imageFiles)) {
                $form->addCustomErrors([new FormError('files', 'You must upload a file', true)]);
            }

            $imageLimit = $this->setting('images_submission_file_limit');
            if (count($imageFiles) > $imageLimit) {
                $form->addCustomErrors([new FormError('files', 'You cannot upload more than {count} images to a collection', true, ['count' => $imageLimit])]);
            }

            if ($form->isValid()) {
                $newSubmission->setCreatorId($this->activeUser()->getId());
                $newSubmission->setSubmitterId($this->activeUser()->getId());
                $newSubmission->setDateAdded(time());
                $newSubmission->setSlug($this->get('slug.generator')->slugify($newSubmission->getName()));
                $newSubmission->setTotalImages(count($imageFiles));

                $submissions->save($newSubmission);

                foreach ($imageFiles as $imageFile) {
                    $imageFile->setCollectionId($newSubmission->getId());

                    $this->model('ImageSubmissionFiles')->save($imageFile);
                }
            }
        }

        return new Response($this->render('@Images/submit_image.twig', ['form' => $form->createView()]));
    }

    public function imagesRssFeedAction()
    {
        /**
         * @var \AVCMS\Bundles\Images\Model\ImageCollection[] $images
         */
        $images = $this->imageCollections->find()->published()->limit(30)->order('publish-date-newest')->get();

        $feed = new RssFeed(
            $this->trans('Images'),
            $this->generateUrl('browse_images', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->trans('The latest images')
        );

        foreach ($images as $image) {
            $url = $this->generateUrl('image_collection', ['slug' => $image->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
            $date = new \DateTime();
            $date->setTimestamp($image->getPublishDate());

            $feed->addItem(new RssItem($image->getName(), $url, $date, $image->getDescription()));
        }

        return new Response($feed->build(), 200, ['Content-Type' => 'application/xml']);
    }
}
