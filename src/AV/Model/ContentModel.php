<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 13:17
 */

namespace AV\Model;

abstract class ContentModel extends Model
{
    public function setPublished($ids, $published = true)
    {
        $ids = (array) $ids;

        if ($published) {
            $published_val = 1;
        }
        else {
            $published_val = 0;
        }

        $this->query()->whereIn('id', $ids)->update(array('published' => $published_val));
    }
}