<?php
/**
 * User: Andy
 * Date: 01/05/2014
 * Time: 19:06
 */

namespace AVCMS\Bundles\Blog\Finder;

use AVCMS\Bundles\Blog\Model\Posts;
use AVCMS\Core\Finder\Finder;

class BlogPostsFinder extends Finder
{
    protected $search_fields = array('title');

    public function __construct(Posts $model)
    {
        parent::__construct($model);

        $this->sort_options = array_merge($this->sort_options, array(
            'user_id' => 'user_id asc'
        ));
    }
} 