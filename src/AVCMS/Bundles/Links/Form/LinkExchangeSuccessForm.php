<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 14:50
 */

namespace AVCMS\Bundles\Links\Form;

use AV\Form\FormBlueprint;

class LinkExchangeSuccessForm extends FormBlueprint
{
    public function __construct($url, $anchor)
    {
        $this->add('html', 'text', [
            'label' => 'HTML',
            'default' => '<a href="'.$url.'">'.$anchor.'</a>'
        ]);

        $this->add('url', 'text', [
            'label' => 'Your unique URL',
            'default' => $url
        ]);

        $this->add('anchor', 'text', [
            'label' => 'Our preferred link anchor',
            'default' => $anchor
        ]);
    }
}
