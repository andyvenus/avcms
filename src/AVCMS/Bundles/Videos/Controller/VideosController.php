<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 20:26
 */

namespace AVCMS\Bundles\Videos\Controller;

use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Videos\Form\VideoFrontendFiltersForm;
use AVCMS\Bundles\Videos\Form\SubmitVideoForm;
use AVCMS\Bundles\Videos\Type\VideoManager;
use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Rss\RssFeed;
use AVCMS\Core\Rss\RssItem;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class VideosController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Videos\Model\Videos
     */
    private $videos;

    /**
     * @var \AVCMS\Bundles\Videos\Model\VideoCategories
     */
    private $videoCategories;

    /**
     * @var VideoManager
     */
    private $videoManager;

    public function setUp() {
        $this->videos = $this->model('Videos');
        $this->videoCategories = $this->model('VideoCategories');
        $this->videoManager = $this->get('video_manager');
    }

    public function playVideoAction(Request $request, $slug)
    {
        $video = $this->videos->find()
            ->published()
            ->slug($slug)
            ->join($this->videoCategories, ['name', 'slug'])
            ->joinTaxonomy('tags')
            ->first();

        if (!$video) {
            throw $this->createNotFoundException('Video Not Found');
        }

        $hitRegistered = $this->container->get('hitcounter')->registerHit($this->videos, $video->getId(), 'hits', 'id', 'last_hit');

        $playsLeft = null;
        if ($hitRegistered && $this->setting('videos_limit_plays') && !$this->userLoggedIn()) {
            $played = $request->cookies->get('avcms_videos_played', 0);
            $playsLeft = $this->setting('videos_limit_plays') - $played;

            if ($playsLeft <= 0) {
                return $this->redirect('login', [], 302, 'info', $this->trans('Please login to continue watching videos'));
            }
            else {
                $playCookie = new Cookie('avcms_videos_played', $played + 1, time() + 1209600);
                $playsLeft--;
            }
        }

        $response = new Response($this->render('@Videos/watch_video.twig', ['video' => $video, 'plays_left' => $playsLeft]));

        if (isset($playCookie)) {
            $response->headers->setCookie($playCookie);
        }

        return $response;
    }

    public function browseVideosAction(Request $request, $pageType = 'archive')
    {
        $finder = $this->videos->find();
        $query = $finder->published()
            ->setResultsPerPage($this->setting('browse_videos_per_page'))
            ->setSearchFields(['videos.name'])
            ->handleRequest($request, [
                'page' => 1,
                'order' => 'publish-date-newest',
                'tags' => null,
                'search' => null,
            ]);

        if ($this->setting('show_video_category')) {
            $query->join($this->videoCategories, ['name', 'slug']);
        }

        $category = null;
        if ($request->get('category') !== null) {
            $category = $this->videoCategories->getFullCategory($request->get('category'));

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
                    throw new AccessDeniedException('You must be logged in to view your liked videos');
                }
            }
            else {
                $user = $this->model('Users')->find()->slug($request->get('likes_user'))->first();

                if (!$user) {
                    throw $this->createNotFoundException();
                }
            }

            $ratings = $this->model('LikeDislike:Ratings');
            $ids = $ratings->getLikedIds($user->getId(), 'video');
            $query = $query->ids($ids, 'videos.id');
        }

        if ($pageType === 'featured') {
            $query->featured();
        }

        $videos = $query->get();

        $formBp = new VideoFrontendFiltersForm();
        $attr = $request->attributes->all();
        $attr['page'] = 1;
        $formBp->setAction($this->generateUrl($request->attributes->get('_route'), $attr));

        $filtersForm = $this->buildForm($formBp, $request);

        return new Response($this->render('@Videos/browse_videos.twig', array(
            'videos' => $videos,
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

    public function submitVideoAction(Request $request)
    {
        if (!$this->isGranted(['PERM_SUBMIT_VIDEOS'])) {
            if (!$this->isGranted(['IS_AUTHENTICATED_FULLY', 'IS_AUTHENTICATED_REMEMBERED'])) {
                return $this->redirect('login', [], 302, 'info', $this->trans('Please login to submit a video'));
            }
            else {
                throw new AccessDeniedException;
            }
        }

        if (!$this->setting('videos_allow_uploads')) {
            throw $this->createNotFoundException();
        }

        $submissions = $this->model('VideoSubmissions');

        $pendingCount = $submissions->query()->where('submitter_id', $this->activeUser()->getId())->count();
        if ($pendingCount >= $this->setting('videos_max_submissions')) {
            return $this->redirect('home', [], 302, 'info', $this->trans('You have already submitted {count} videos, please wait for them to be accepted first', ['count' => $this->setting('videos_max_submissions')]));
        }

        $newSubmission = $submissions->newEntity();

        $formBlueprint = new SubmitVideoForm(new CategoryChoicesProvider($this->model('VideoCategories')));

        $form = $this->buildForm($formBlueprint, $request, $newSubmission);

        if ($form->isValid()) {
            try {
                $importer = $this->videoManager->getImporterForUrl($form->getData('file'));
            } catch (\Exception $e) {
                $form->setError('file', 'The entered video URL is not supported');
            }
        }

        if ($form->isValid()) {
            $videoExists = $this->videos->query()
                ->where('provider', $importer->getId())
                ->where('provider_id', $importer->getIdFromUrl($form->getData('file')))
                ->count();

            $videoExists += $submissions->query()
                ->where('provider', $importer->getId())
                ->where('provider_id', $importer->getIdFromUrl($form->getData('file')))
                ->count();

            if ($videoExists) {
                $form->setError('file', 'This video has already been submitted');
            }
        }

        if ($form->isValid()) {
            $form->saveToEntities();

            $importer->getVideoAtUrl($form->getData('file'), $newSubmission);

            $newSubmission->setSlug($this->get('slug.generator')->slugify($newSubmission->getName()));

            $newSubmission->setCreatorId($this->activeUser()->getId());
            $newSubmission->setSubmitterId($this->activeUser()->getId());
            $newSubmission->setDateAdded(time());

            $submissions->save($newSubmission);

        }

        return new Response($this->render(
            '@Videos/submit_video.twig',
            [
                'form' => $form->createView(),
                'providers' => $this->videoManager->getTypes(),
                'submission' => $newSubmission
            ]
        ));
    }

    public function videosRssFeedAction()
    {
        /**
         * @var \AVCMS\Bundles\Videos\Model\Video[] $videos
         */
        $videos = $this->videos->find()->published()->limit(30)->order('publish-date-newest')->get();

        $feed = new RssFeed(
            $this->trans('Videos'),
            $this->generateUrl('browse_videos', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->trans('The latest videos')
        );

        foreach ($videos as $video) {
            $url = $this->generateUrl('watch_video', ['slug' => $video->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
            $date = new \DateTime();
            $date->setTimestamp($video->getPublishDate());

            $feed->addItem(new RssItem($video->getName(), $url, $date, $video->getDescription()));
        }

        return new Response($feed->build(), 200, ['Content-Type' => 'application/xml']);
    }

    public function testAction(Request $request)
    {
        $importer = $this->videoManager;

        $video = $importer->getVideoDetails($request->get('url'));

        dump($video);exit;
    }
}
