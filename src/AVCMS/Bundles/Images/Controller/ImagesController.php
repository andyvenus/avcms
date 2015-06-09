<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 20:26
 */

namespace AVCMS\Bundles\Images\Controller;

use AV\FileHandler\UploadedFileHandler;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Images\Form\ImageFrontendFiltersForm;
use AVCMS\Bundles\Images\Form\SubmitImageForm;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Rss\RssFeed;
use AVCMS\Core\Rss\RssItem;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ImagesController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Images\Model\ImageCollections
     */
    private $images;

    private $imageCategories;

    /**
     * @var \AVCMS\Bundles\Images\Model\ImageFiles
     */
    private $imageFiles;

    public function setUp() {
        $this->images = $this->model('ImageCollections');
        $this->imageFiles = $this->model('ImageFiles');
        $this->imageCategories = $this->model('ImageCategories');
    }

    public function imageCollectionAction(Request $request, $slug)
    {
        $imageCollection = $this->images->find()
            ->published()
            ->slug($slug)
            ->join($this->imageCategories, ['name', 'slug'])
            ->joinTaxonomy('tags')
            ->first();

        $imageCollection->files = $this->imageFiles->getImageFiles($imageCollection->getId());

        if (!$imageCollection) {
            throw $this->createNotFoundException('Image Not Found');
        }

        $hitRegistered = $this->container->get('hitcounter')->registerHit($this->images, $imageCollection->getId(), 'hits', 'id', 'last_hit');

        $playsLeft = null;
        if ($hitRegistered && $this->setting('images_limit_plays') && !$this->userLoggedIn()) {
            $played = $request->cookies->get('avcms_images_played', 0);
            $playsLeft = $this->setting('images_limit_plays') - $played;

            if ($playsLeft <= 0) {
                return $this->redirect('login', [], 302, 'info', $this->trans('Please login to continue playing images'));
            }
            else {
                $playCookie = new Cookie('avcms_images_played', $played + 1, time() + 1209600);
                $playsLeft--;
            }
        }

        $response = new Response($this->render('@Images/image_collection.twig', ['image_collection' => $imageCollection, 'plays_left' => $playsLeft]));
        if (isset($playCookie)) {
            $response->headers->setCookie($playCookie);
        }

        return $response;
    }

    public function browseImagesAction(Request $request, $pageType = 'archive')
    {
        $finder = $this->images->find();
        $query = $finder->published()
            ->setResultsPerPage($this->setting('browse_images_per_page'))
            ->setSearchFields(['images.name'])
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
                $query->category($category->getId());
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
                $user = $this->model('Users')->findOne($request->get('likes_user'))->first();

                if (!$user) {
                    throw $this->createNotFoundException();
                }
            }

            $ratings = $this->model('LikeDislike:Ratings');
            $ids = $ratings->getLikedIds($user->getId(), 'image');
            $query = $query->ids($ids, 'images.id');
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

            $submissionsImagesDir = $this->getParam('images_dir').'/submissions';
            if (!file_exists($submissionsImagesDir)) {
                mkdir($submissionsImagesDir, 0777, true);
            }

            $submissionsThumbsDir = $this->getParam('image_thumbnails_dir').'/submissions';
            if (!file_exists($submissionsThumbsDir)) {
                mkdir($submissionsThumbsDir, 0777, true);
            }

            $fullFilePath = $fileHandler->moveFile($form->getData('file'), $submissionsImagesDir);
            $fileRelPath = str_replace($this->getParam('images_dir').'/', '', $fullFilePath);
            $newSubmission->setFile($fileRelPath);

            $fullThumbPath = $fileHandler->moveFile($form->getData('thumbnail'), $submissionsThumbsDir);
            $thumbRelPath = str_replace($this->getParam('image_thumbnails_dir').'/', '', $fullThumbPath);
            $newSubmission->setThumbnail($thumbRelPath);

            $newSubmission->setCreatorId($this->activeUser()->getId());
            $newSubmission->setSubmitterId($this->activeUser()->getId());
            $newSubmission->setDateAdded(time());

            $dimensions = @getimagesize($fullFilePath);

            if (!$dimensions) {
                $dimensions = [0, 0];
            }

            $newSubmission->setWidth($dimensions[0]);
            $newSubmission->setHeight($dimensions[1]);

            $newSubmission->setSlug($this->get('slug.generator')->slugify($newSubmission->getName()));

            $submissions->save($newSubmission);

        }

        return new Response($this->render('@Images/submit_image.twig', ['form' => $form->createView()]));
    }

    public function imagesRssFeedAction()
    {
        /**
         * @var \AVCMS\Bundles\Images\Model\ImageCollection[] $images
         */
        $images = $this->images->find()->published()->limit(30)->order('publish-date-newest')->get();

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
