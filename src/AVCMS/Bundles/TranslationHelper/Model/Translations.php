<?php

namespace AVCMS\Bundles\TranslationHelper\Model;

use AV\Model\Model;

class Translations extends Model
{
    public function getTable()
    {
        return 'translations';
    }

    public function getSingular()
    {
        return 'translation';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\TranslationHelper\Model\Translation';
    }
}