<?php
/**
 * User: Andy
 * Date: 05/01/15
 * Time: 18:57
 */

namespace AVCMS\Bundles\Wallpapers\Form;

use AV\Form\FormBlueprint;

class WallpaperFrontendFiltersForm extends FormBlueprint
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
                'top-downloads' => 'Most Downloaded',
                'a-z' => 'A-Z',
                'z-a' => 'Z-A',
            ]
        ]);
    }
}
