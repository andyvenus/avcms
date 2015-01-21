<?php

namespace AVCMS\Bundles\Links\Form;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\Referrals\Form\ChoicesProvider\ReferralsChoicesProvider;

class LinkAdminForm extends FormBlueprint
{
    public function __construct(ReferralsChoicesProvider $referralsChoicesProvider)
    {
        $this->add('anchor', 'text', array(
            'label' => 'Anchor',
            'required' => true
        ));
        
        $this->add('url', 'text', array(
            'label' => 'URL',
            'required' => true,
            'transform' => 'url',
        ));
        
        $this->add('description', 'textarea', array(
            'label' => 'Description',
        ));
        
        $this->add('referral_id', 'select', array(
            'label' => 'Referrer',
            'choices_provider' => $referralsChoicesProvider
        ));
    }
}
