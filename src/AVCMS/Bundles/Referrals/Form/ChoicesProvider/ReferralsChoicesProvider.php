<?php
/**
 * User: Andy
 * Date: 21/01/15
 * Time: 12:34
 */

namespace AVCMS\Bundles\Referrals\Form\ChoicesProvider;

use AV\Form\ChoicesProviderInterface;
use AVCMS\Bundles\Referrals\Model\Referrals;

class ReferralsChoicesProvider implements ChoicesProviderInterface
{
    protected $referrals;

    protected $noValue;

    public function __construct(Referrals $referrals, $noValue = null)
    {
        $this->referrals = $referrals;
        $this->noValue = $noValue;
    }

    public function getChoices()
    {
        $referrals = $this->referrals->getAll();

        $choices = [];

        if ($this->noValue !== null) {
            $choices[0] = $this->noValue;
        }

        foreach ($referrals as $referral) {
            $choices[$referral->getId()] = $referral->getName();
        }

        return $choices;
    }
}
