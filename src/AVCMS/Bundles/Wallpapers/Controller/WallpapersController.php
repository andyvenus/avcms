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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            $category = $categories->findOne($request->get('category'))->first();

            if (!$category) {
                throw $this->createNotFoundException();
            }

            $categoryId = $category->getId();

            if ($category->getParent()) {
                $category->parent = $categories->getOne($category->getParent());
            }
            else {
                $category->subcategories = $categories->query()->where('parent', $categoryId)->get();
            }

            $query->category($categoryId);
        }
        if ($pageType === 'featured') {
            $query->featured();
        }

        $wallpapers = $query->get();

        $formBp = new WallpaperFrontendFiltersForm($this->get('wallpaper.resolutions_manager')->getAllResolutions());
        $attr = $request->attributes->all();
        $attr['page'] = 1;
        $formBp->setAction($this->generateUrl($request->attributes->get('_route'), $attr));
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

        $this->container->get('hitcounter')->registerHit($this->wallpapers, $wallpaper->getId());

        return new Response($this->render('@Wallpapers/wallpaper_details.twig', ['wallpaper' => $wallpaper, 'resolutions' => $resolutions]));
    }

    public function wallpaperPreviewAction(Request $request, $slug)
    {
        $wallpaper = $this->wallpapers->findOne($slug)->first();

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
                return $this->redirect($this->generateUrl('login'), 302, 'info', $this->trans('Please login to submit a wallpaper'));
            }
            else {
                throw new AccessDeniedException;
            }
        }

        if (!$this->setting('wallpapers_allow_uploads')) {
            throw $this->createNotFoundException();
        }

        $submissions = $this->model('WallpaperSubmissions');

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
}
