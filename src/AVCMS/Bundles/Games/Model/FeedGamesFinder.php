<?php
/**
 * User: Andy
 * Date: 09/02/15
 * Time: 17:03
 */

namespace AVCMS\Bundles\Games\Model;

use AV\Model\Finder;

class FeedGamesFinder extends Finder
{
    public function status($status = null)
    {
        if ($status !== 'pending' && $status !== 'rejected' && $status !== 'imported' && $status !== 'all') {
            $status = 'pending';
        }

        if ($status !== 'all') {
            $this->currentQuery->where('status', $status);
        }

        return $this;
    }

    public function feed($feed = null)
    {
        if ($feed) {
            $this->currentQuery->where('provider', $feed);
        }

        return $this;
    }

    public function fileType($filetype = null)
    {
        if ($filetype) {
            $this->currentQuery->where('file_type', $filetype);
        }

        return $this;
    }

    public function category($term)
    {
        if (!$term) {
            return $this;
        }

        $this->currentQuery->where('category', 'LIKE', '%'.$term.'%');
    }
}
