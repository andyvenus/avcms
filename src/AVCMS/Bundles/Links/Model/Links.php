<?php

namespace AVCMS\Bundles\Links\Model;

use AV\Model\Finder;
use AV\Model\Model;
use AVCMS\Bundles\Referrals\Model\Referrals;

class Links extends Model
{
    public function getTable()
    {
        return 'links';
    }

    public function getSingular()
    {
        return 'link';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Links\Model\Link';
    }

    public function getFinderSortOptions()
    {
        return array(
            'a-z' => 'anchor asc',
            'z-a' => 'anchor desc',
        );
    }

    /**
     * @param Referrals $referrals
     * @param $page
     * @param int $itemsPerPage
     * @param bool $published
     * @return Finder
     */
    public function getTopLinksFinder(Referrals $referrals, $page, $itemsPerPage = 25, $published = true)
    {
        $q = $this->find()
            ->customOrder($this->query()->raw('inbound - outbound'), 'DESC')
            ->setResultsPerPage($itemsPerPage)
            ->page($page)
            ->join($referrals, ['inbound', 'outbound']);

        if ($published) {
            $q->published();
        }

        return $q;
    }
}
