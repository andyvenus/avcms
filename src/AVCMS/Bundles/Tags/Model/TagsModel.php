<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 13:55
 */

namespace AVCMS\Bundles\Tags\Model;

use AV\Model\Model;

class TagsModel extends Model
{

    public function getTable()
    {
        return 'tags';
    }

    public function getSingular()
    {
        return 'tag';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Tags\Model\Tag';
    }
}
