<?php
/**
 * User: Andy
 * Date: 26/01/15
 * Time: 13:34
 */

namespace AVCMS\Bundles\WssImporter\Controller;

use AVCMS\Bundles\Tags\Model\Tag;
use AVCMS\Bundles\Wallpapers\Model\Wallpaper;
use AVCMS\Bundles\Wallpapers\Model\WallpaperCategory;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WssImporterController extends Controller
{
    protected $connection;

    public function importerHomeAction(Request $request)
    {
        // WALLPAPERS //
        $wallpapers = $this->getQB('wallpapers')->get();

        $wallpapersProcessed = [];
        foreach ($wallpapers as $wp) {
            $this->renameFields($wp, [
                'category' => 'category_id',
                'category_parent' => 'category_parent_id',
                'seo_url' => 'slug',
                'downloads' => 'total_downloads',
                'display' => 'resize_type',
                'submitter' => 'submitter_id'
            ]);

            //$wp['file'] = preg_replace('/files\//', '', $wp['file'], 1);
            $wp['creator_id'] = $this->activeUser()->getId();

            $wp['publish_date'] = ($wp['date_added'] ? $wp['date_added'] : time());

            $wp['crop_position'] = str_replace('middle-', '', $wp['valign'].'-'.$wp['align']);

            $wallpaper = new Wallpaper();
            $wallpaper->fromArray($wp, true);

            $wallpapersProcessed[] = $wallpaper;
        }

        $this->model('AVCMS\Bundles\Wallpapers\Model\Wallpapers')->insert($wallpapersProcessed);


        // WALLPAPER CATEGORIES //
        $categories = $this->getQB('cats')->get();

        $catsProcessed = [];
        foreach ($categories as $cat) {
            $this->renameFields($cat, [
                'cat_order' => 'order',
                'seo_url' => 'slug',
                'parent_id' => 'parent',
            ]);

            $nuCat = new WallpaperCategory();
            $nuCat->fromArray($cat, true);

            $catsProcessed[] = $nuCat;
        }

        $this->model('AVCMS\Bundles\Wallpapers\Model\WallpaperCategories')->insert($catsProcessed);


        // TAGS //
        $tags = $this->getQB('tags')->get();

        $tagsProcessed = [];
        foreach ($tags as $t) {
            $this->renameFields($t, [
                'tag_name' => 'name',
            ]);

            $tag = new Tag();
            $tag->fromArray($t, true);

            $tagsProcessed[] = $tag;
        }

        $this->model('AVCMS\Bundles\Tags\Model\TagsModel')->insert($tagsProcessed);

        // TAG RELATIONS //
        $relations = $this->getQB('tag_relations')->get();

        foreach ($relations as $r) {
            $this->renameFields($r, [
                'tag_id' => 'taxonomy_id',
                'wallpaper_id' => 'content_id'
            ]);

            $r['content_type'] = 'wallpaper';

            unset($r['id']);

            $this->model('AVCMS\Bundles\Tags\Model\TagsTaxonomyModel')->query()->insert($r);
        }

        return new Response('test');
    }

    protected function renameFields(&$row, $renames)
    {
        foreach ($row as $fieldName => $fieldVal) {
            if (isset($renames[$fieldName])) {
                $row[$renames[$fieldName]] = $fieldVal;
                unset($row[$fieldName]);
            }
        }
    }

    /**
     * @return \Pixie\Connection
     */
    protected function getDatabaseConnection()
    {
        if (isset($this->connection)) {
            return $this->connection;
        }

        $config = array(
            'driver'    => 'mysql', // Db driver
            'host'      => 'localhost',
            'database'  => 'wss',
            'username'  => 'root',
            'password'  => 'root',
            'prefix'    => 'wss_', // Table prefix, optional
        );

        return $this->connection = new \Pixie\Connection('mysql', $config, 'QB');
    }

    /**
     * @param $table
     * @return \Pixie\QueryBuilder\QueryBuilderHandler
     */
    protected function getQB($table)
    {
        $qb = $this->getDatabaseConnection()->getQueryBuilder()->table($table);
        $qb->setFetchMode(\PDO::FETCH_ASSOC);

        return $qb;
    }
}
