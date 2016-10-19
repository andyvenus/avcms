<?php
/**
 * User: Andy
 * Date: 26/05/15
 * Time: 13:18
 */

namespace AVCMS\Bundles\Wallpapers\Sitemaps;

use AVCMS\Bundles\Wallpapers\Model\Wallpapers;
use AVCMS\Bundles\Wallpapers\ResolutionsManager\ResolutionsManager;
use AVCMS\Core\SettingsManager\SettingsManager;
use AVCMS\Core\Sitemaps\ContentSitemap;
use AVCMS\Core\Sitemaps\SitemapImage;
use AVCMS\Core\Sitemaps\SitemapLink;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WallpaperPreviewsSitemap extends ContentSitemap
{
    private $resolutionsManager;

    public function __construct(Wallpapers $model, UrlGeneratorInterface $urlGenerator, ResolutionsManager $resolutionsManager, SettingsManager $settings)
    {
        $this->resolutionsManager = $resolutionsManager;
        $this->settings = $settings;

        parent::__construct($model, $urlGenerator, 'wallpaper_preview');
    }

    public function getId()
    {
        return 'wallpaper_previews';
    }

    public function getSitemapLinks($page)
    {
        $resolutions = $this->resolutionsManager->getAllUniqueResolutions();
        $itemsPerPage = floor(self::LINKS_PER_PAGE / count($resolutions));

        /**
         * @var \AVCMS\Core\Model\ContentEntity[] $items
         */
        $finder = $this->model->find()
            ->setResultsPerPage($itemsPerPage)
            ->page($page)
            ->published()
        ;

        $finder->getQuery()->select(['name', 'date_added', 'date_edited', 'slug', 'file', 'original_width', 'original_height']);

        $items = $finder->get();

        $links = [];

        foreach ($items as $wallpaper) {
            foreach ($resolutions as $resolution) {
                $dimensions = explode('x', $resolution);

                // No sitemap for resolutions larger than the source
                if (!$this->settings->getSetting('show_higher_resolutions') && ($dimensions[0] > $wallpaper->getOriginalWidth() || $dimensions[1] > $wallpaper->getOriginalHeight())) {
                    continue;
                }

                try {
                    $url = $this->urlGenerator->generate(
                        $this->routeName,
                        ['slug' => $wallpaper->getSlug(), 'resolution' => $resolution],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );

                    $timestamp = ($wallpaper->getDateEdited() ? $wallpaper->getDateEdited() : $wallpaper->getDateAdded());
                    $dateModified = new \DateTime();
                    $dateModified->setTimestamp($timestamp);
                }
                catch (\Exception $e) {
                    continue;
                }

                $extension = pathinfo($wallpaper->getFile())['extension'];

                $imageUrlParameters = ['slug' => $wallpaper->getSlug(), 'width' => $dimensions[0], 'height' => $dimensions[1], 'ext' => $extension];
                $imageUrl = $this->urlGenerator->generate('wallpaper_image', $imageUrlParameters, UrlGeneratorInterface::ABSOLUTE_URL);
                $image = new SitemapImage($imageUrl, $wallpaper->getName().' - '.$resolution);

                $link = new SitemapLink($url, $dateModified, [$image]);

                $links[] = $link;
            }
        }

        return $links;
    }
}
