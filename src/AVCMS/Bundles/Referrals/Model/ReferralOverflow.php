<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 11:31
 */

namespace AVCMS\Bundles\Referrals\Model;

use AV\Model\ExtensionEntity;

class ReferralOverflow extends ExtensionEntity
{
    public function getPrefix()
    {
        return 'referral';
    }

    public function getReferral()
    {
        $this->get('referral');
    }

    public function setReferral($value)
    {
        $this->set('referral', $value);
    }
}
