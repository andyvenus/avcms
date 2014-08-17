<?php
/**
 * User: Andy
 * Date: 01/05/2014
 * Time: 19:06
 */

namespace AVCMS\Bundles\Blog\Finder;

use AVCMS\Bundles\Blog\Model\Posts;
use AVCMS\Core\Model\Finder;
use AVCMS\Core\Taxonomy\TaxonomyManager;

class BlogPostsFinder extends Finder
{
    protected $search_fields = array('title');

    public function __construct(Posts $model, TaxonomyManager $taxonomy_manager = null)
    {
        parent::__construct($model, $taxonomy_manager);

        $this->sort_options = array_merge($this->sort_options, array(
            'user_id' => 'user_id asc',
            'publish_date_newest' => 'publish_date desc',
            'publish_date_oldest' => 'publish_date asc',
            'a_z' => 'title asc',
            'z_a' => 'title desc'
        ));
    }
} 