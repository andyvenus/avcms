<?php

namespace AVCMS\Bundles\Wallpapers\Form;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;

class WallpapersAdminFiltersForm extends AdminFiltersForm
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
                'top-hits' => 'Top Hits',
                'low-hits' => 'Lowest Hits',
                'top-downloads' => 'Most Downloaded',
                'liked' => 'Most Liked'
            )
        ));

        $this->addBefore('search', 'category', 'select', array(
            'choices_provider' => $categoryChoicesProvider
        ));
    }
}
