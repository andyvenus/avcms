<?php

namespace AVCMS\Bundles\Images\Form;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;

class ImagesAdminFiltersForm extends AdminFiltersForm
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
                'top-hits' => 'Most Viewed',
                'low-hits' => 'Least Viewed',
                'liked' => 'Most Liked',
                'last-hit-desc' => 'Recent Last View',
                'last-hit-asc' => 'Oldest Last View'
            )
        ));

        $this->addBefore('search', 'category', 'select', array(
            'choices_provider' => $categoryChoicesProvider
        ));
    }
}
