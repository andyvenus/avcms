<?php
/**
 * User: Andy
 * Date: 11/02/15
 * Time: 14:24
 */

namespace AVCMS\Bundles\Images\Form;

use AV\Form\FormBlueprint;

class ImageFrontendFiltersForm extends FormBlueprint
{
    public function __construct()
    {
        $this->setMethod('GET');

        $this->add('order', 'select', [
            'label' => 'Order',
            'choices' => [
                'publish-date-newest' => 'Newest',
                'publish-date-oldest' => 'Oldest',
                'liked' => 'Most Liked',
                'top-hits' => 'Most Viewed',
                'a-z' => 'A-Z',
                'z-a' => 'Z-A',
            ],
            'allow_unset' => true
        ]);

        $this->add('search', 'text', [
            'label' => 'Search'
        ]);

    }
}
