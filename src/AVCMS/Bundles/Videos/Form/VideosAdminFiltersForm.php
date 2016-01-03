<?php

namespace AVCMS\Bundles\Videos\Form;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;

class VideosAdminFiltersForm extends AdminFiltersForm
{
    public function __construct(CategoryChoicesProvider $categoryChoicesProvider)
    {
        parent::__construct();

        $this->replace('order', 'select', array(
            'choices' => array(
                'newest' => 'Newest (by ID)',
                'oldest' => 'Oldest (by ID)',
                'publish-date-newest' => 'Newest (by publish date)',
                'publish-date-oldest' => 'Oldest (by publish date)',
                'a-z' => 'A-Z',
                'z-a' => 'Z-A',
                'top-hits' => 'Most Played',
                'low-hits' => 'Least Played',
                'liked' => 'Most Liked',
                'last-hit-desc' => 'Recent Last Watch',
                'last-hit-asc' => 'Oldest Last Watch',
                'longest' => 'Longest Duration',
                'shortest' => 'Shortest Duration',
            )
        ));

        $this->addBefore('search', 'category', 'select', array(
            'choices_provider' => $categoryChoicesProvider
        ));
    }
}
