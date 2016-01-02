<?php
/**
 * User: Andy
 * Date: 26/12/2015
 * Time: 18:00
 */

namespace AVCMS\Bundles\Videos\Controller;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Videos\Form\YouTubeFiltersForm;
use AVCMS\Bundles\Videos\Model\Videos;
use AVCMS\Bundles\Videos\Type\VideoManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImportVideosAdminController extends AdminBaseController
{
    /**
     * @var VideoManager
     */
    private $videoManager;

    /**
     * @var Videos
     */
    private $videos;

    public function setUp()
    {
        $this->videos = $this->model('Videos');
        $this->videoManager = $this->get('video_manager');
    }

    public function homeAction(Request $request)
    {
        return $this->handleManage($request, '@Videos/admin/import_videos_browser.twig');
    }

    public function finderAction(Request $request)
    {
        $videos = $this->videoManager->getType('youtube')->searchVideos($request->query->all(), $request->get('page'));

        foreach ($videos as $video) {
            $providerIds[] = $video->getProviderId();
        }

        $existing = [];

        if (isset($providerIds)) {
            $existingQ = $this->videos->query()
                ->select(['id', 'provider_id'])
                ->whereIn('provider_id', $providerIds)
                ->where('provider', 'youtube')
                ->get(null, \PDO::FETCH_ASSOC);

            foreach ($existingQ as $existingRow) {
                $existing[$existingRow['provider_id']] = $existingRow['id'];
            }
        }

        return new Response($this->render('@Videos/admin/import_videos_finder.twig', array(
            'items' => $videos,
            'page' => intval($request->get('page')),
            'existing' => $existing,
            'categoriesForm' => $this->getCategoriesForm(),
        )));
    }

    public function importAction(Request $request)
    {
        $chunkedIds = array_chunk((array) $request->get('ids'), 50);
        $categories = (array) $request->get('categories');

        $urls = [];
        $videoCount = 0;

        foreach ($chunkedIds as $ids) {
            $videos = $this->videoManager->getType('youtube')->getVideosById($ids);

            foreach ($videos as $video) {
                $video->setDateAdded(time());
                $video->setPublishDate(time());
                $video->setPublished(1);

                $slug = $this->container->get('slug.generator')->slugify($video->getName());
                if ($this->videos->query()->where('slug', $slug)->count() > 0) {
                    $slug .= '-' . time();
                }
                $video->setSlug($slug);

                if (isset($categories[$video->getProviderId()])) {
                    $video->setCategoryId($categories[$video->getProviderId()]);
                }

                $this->videos->save($video);

                $urls[$video->getProviderId()] = $this->generateUrl('videos_admin_edit', ['id' => $video->getId()]);

                $videoCount += count($videos);
            }
        }

        return new JsonResponse(['success' => true, 'imported_count' => $videoCount, 'urls' => $urls]);
    }

    private function getCategoriesForm($bulk = false)
    {
        $form = new FormBlueprint();

        $options = [
            'choices_provider' => new CategoryChoicesProvider($this->model('VideoCategories'))
        ];

        if ($bulk) {
            $options['choices'] = ['0' => 'Use Selected Category'];
        }

        $form->add('category', 'select', $options);

        $form = $this->buildForm($form);

        return $form->createView();
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new YouTubeFiltersForm())->createView();

        $templateVars['bulk_categories_form'] = $this->getCategoriesForm(true);

        return $templateVars;
    }
}
