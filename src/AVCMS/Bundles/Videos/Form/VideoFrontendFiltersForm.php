<?php
/**
 * User: Andy
 * Date: 11/02/15
 * Time: 14:24
 */

namespace AVCMS\Bundles\Videos\Form;

use AV\Form\FormBlueprint;

class VideoFrontendFiltersForm extends FormBlueprint
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
                'top-hits' => 'Most Played',
                'a-z' => 'A-Z',
                'z-a' => 'Z-A',
            ],
            'allow_unset' => true
        ]);

        $this->add('search', 'text', [
            'label' => 'Search'
        ]);


        $this->add('mobile_only', 'checkbox', [
            'label' => 'Show only mobile videos'
        ]);

    }
}
