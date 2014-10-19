<?php
/**
 * User: Andy
 * Date: 01/05/2014
 * Time: 19:06
 */

namespace AVCMS\Bundles\Blog\Finder;

use AVCMS\Bundles\Blog\Model\Posts;
use AV\Model\Finder;
use AVCMS\Core\Taxonomy\TaxonomyManager;

class BlogPostsFinder extends Finder
{
    protected $searchFields = array('title');

    public function __construct(Posts $model, TaxonomyManager $taxonomyManager = null)
    {
        parent::__construct($model, $taxonomyManager);

        $this->sortOptions = array_merge($this->sortOptions, array(
            'user_id' => 'user_id asc',
            'publish_date_newest' => 'publish_date desc',
            'publish_date_oldest' => 'publish_date asc',
            'a_z' => 'title asc',
            'z_a' => 'title desc'
        ));
    }
} 