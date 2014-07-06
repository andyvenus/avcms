<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 14:05
 */

namespace AVCMS\Bundles\Tags\Model;

use AVCMS\Core\Taxonomy\Model\TaxonomyModel;

class TagsTaxonomyModel extends TaxonomyModel
{
    public function getTable()
    {
        return 'tag_taxonomy';
    }

    public function getSingular()
    {
        return 'tag_taxonomy';
    }
}