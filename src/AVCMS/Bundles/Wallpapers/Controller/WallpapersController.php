<?php
/**
 * User: Andy
 * Date: 13/12/14
 * Time: 22:35
 */

namespace AVCMS\Bundles\Wallpapers\Controller;

use AV\FileHandler\UploadedFileHandler;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Wallpapers\Form\SubmitWallpaperForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperFrontendFiltersForm;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Rss\RssItem;
use AVCMS\Core\Rss\RssFeed;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class WallpapersController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Wallpapers\Model\Wallpapers
     */
    private $wallpapers;

    public function setUp()
    {
        $this->wallpapers = $this->model('Wallpapers');
    }

    public function wallpapersHomeAction(Request $request)
    {
        return new Response($this->render('@Wallpapers/wallpapers_home.twig'));
    }

    public function browseWallpapersAction(Request $request, $pageType = 'archive')
    {
        $finder = $this->wallpapers->find();
        $query = $finder->published()
            ->setResultsPerPage($this->setting('browse_wallpapers_per_page'))
            ->setSearchFields(['name'])
            ->handleRequest($request, array(
                'page' => 1,
                'order' => 'publish-date-newest',
                'tags' => null,
                'search' => null,
                'resolution' => null
            ));

        $category = null;
        if ($request->get('category') !== null) {
            $categories = $this->model('WallpaperCategories');
            $category = $categories->getFullCategory($request->get('category'));

            $query->category($category->getId());
        }

        if ($pageType == 'likes') {
            if ($request->get('likes_user') === null) {
                $user = $this->activeUser();

                if (!$user->getId()) {
                    throw new AccessDeniedException('You must be logged in to view your liked games');
                }
            }
            else {
                $user = $this->model('Users')->findOne($request->get('likes_user'))->first();

                if (!$user) {
                    throw $this->createNotFoundException();
                }
            }

            $ratings = $this->model('LikeDislike:Ratings');
            $ids = $ratings->getLikedIds($user->getId(), 'wallpaper', $this->setting('browse_wallpapers_per_page'));
            $query = $query->ids($ids, 'wallpapers.id');
        }

        if ($pageType === 'featured') {
            $query->featured();
        }

        $wallpapers = $query->get();

        $formBp = new WallpaperFrontendFiltersForm($this->get('wallpaper.resolutions_manager')->getAllResolutions());
        $attr = $request->attributes->all();
        $attr['page'] = 1;
        $formBp->setAction($this->generateUrl($request->attributes->get('_route'), $attr));

        if ($request->get('_route') === 'wallpaper_browse_resolution') {
            $formBp->remove('resolution');
        }

        $filtersForm = $this->buildForm($formBp, $request);

        return new Response($this->render('@Wallpapers/browse_wallpapers.twig', array(
            'wallpapers' => $wallpapers,
            'total_pages' => $finder->getTotalPages(),
            'current_page' => $finder->getCurrentPage(),
            'page_type' => $pageType,
            'category' => $category,
            'filters_form' => $filtersForm->createView(),
            'finder_request' => $finder->getRequestFilters()
        )));
    }

    public function wallpaperDetailsAction(Request $request, $slug)
    {
        $wallpaper = $this->wallpapers->find()
            ->slug($request->get('slug'))
            ->published()
            ->join($this->model('WallpaperCategories'), ['id', 'slug', 'name'])
            ->joinTaxonomy('tags')
            ->first();

        if (!$wallpaper) {
            throw $this->createNotFoundException('Wallpaper Not Found');
        }

        if ($wallpaper->getSubmitterId()) {
            $wallpaper->submitter = $this->model('@users')->getOne($wallpaper->getSubmitterId());
        }

        $resolutions = $this->container->get('wallpaper.resolutions_manager')->getWallpaperResolutions($wallpaper);

        $this->container->get('hitcounter')->registerHit($this->wallpapers, $wallpaper->getId(), 'hits', 'id', 'last_hit');

        return new Response($this->render('@Wallpapers/wallpaper_details.twig', ['wallpaper' => $wallpaper, 'resolutions' => $resolutions]));
    }

    public function wallpaperPreviewAction(Request $request, $slug)
    {
        $wallpaper = $this->wallpapers->find()->slug($slug)->published()->first();

        list($width, $height) = explode('x', $request->get('resolution'));

        if (!$width || !$height) {
            throw $this->createNotFoundException('Not a valid resolution');
        }

        if (!$wallpaper) {
            throw $this->createNotFoundException('Wallpaper Not Found');
        }

        return new Response($this->render('@Wallpapers/wallpaper_preview.twig', ['wallpaper' => $wallpaper, 'wallpaper_width' => $width, 'wallpaper_height' => $height]));
    }

    public function submitWallpaperAction(Request $request)
    {
        if (!$this->isGranted(['PERM_SUBMIT_WALLPAPERS'])) {
            if (!$this->isGranted(['IS_AUTHENTICATED_FULLY', 'IS_AUTHENTICATED_REMEMBERED'])) {
                return $this->redirect('login', [], 302, 'info', $this->trans('Please login to submit a wallpaper'));
            }
            else {
                throw new AccessDeniedException;
            }
        }

        if (!$this->setting('wallpapers_allow_uploads')) {
            throw $this->createNotFoundException();
        }

        $submissions = $this->model('WallpaperSubmissions');

        $pendingCount = $submissions->query()->where('submitter_id', $this->activeUser()->getId())->count();
        if ($pendingCount >= $this->setting('wallpapers_max_submissions')) {
            return $this->redirect('home', [], 302, 'info', $this->trans('You have already submitted {count} wallpapers, please wait for them to be accepted first', ['count' => $this->setting('wallpapers_max_submissions')]));
        }

        $newSubmission = $submissions->newEntity();

        $formBlueprint = new SubmitWallpaperForm(new CategoryChoicesProvider($this->model('WallpaperCategories')));

        $form = $this->buildForm($formBlueprint, $request, $newSubmission);

        if ($form->isValid()) {
            $form->saveToEntities();

            $fileHandler = new UploadedFileHandler();

            $submissionsDir = $this->bundle->config->wallpapers_dir.'/submissions';
            if (!file_exists($submissionsDir)) {
                mkdir($submissionsDir, 0777, true);
            }

            $fullPath = $fileHandler->moveFile($form->getData('file'), $this->bundle->config->wallpapers_dir.'/submissions');
            $relPath = str_replace($this->bundle->config->wallpapers_dir.'/', '', $fullPath);

            $newSubmission->setFile($relPath);

            $newSubmission->setCreatorId($this->activeUser()->getId());
            $newSubmission->setSubmitterId($this->activeUser()->getId());
            $newSubmission->setDateAdded(time());

            $dimensions = getimagesize($fullPath);

            $newSubmission->setOriginalWidth($dimensions[0]);
            $newSubmission->setOriginalHeight($dimensions[1]);

            $submissions->save($newSubmission);

        }

        return new Response($this->render('@Wallpapers/submit_wallpaper.twig', ['form' => $form->createView()]));
    }

    public function wallpapersRssFeedAction()
    {
        /**
         * @var \AVCMS\Bundles\Wallpapers\Model\Wallpaper[] $wallpapers
         */
        $wallpapers = $this->wallpapers->find()->published()->limit(30)->order('publish-date-newest')->get();

        $feed = new RssFeed(
            $this->trans('Wallpapers'),
            $this->generateUrl('browse_wallpapers', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->trans('The latest wallpapers')
        );

        foreach ($wallpapers as $wallpaper) {
            $url = $this->generateUrl('wallpaper_details', ['slug' => $wallpaper->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
            $date = new \DateTime();
            $date->setTimestamp($wallpaper->getPublishDate());

            $feed->addItem(new RssItem($wallpaper->getName(), $url, $date, $wallpaper->getDescription()));
        }

        return new Response($feed->build(), 200, ['Content-Type' => 'application/xml']);
    }
}
