<?php
/**
 * User: Andy
 * Date: 17/07/2014
 * Time: 12:45
 */

namespace AVCMS\Test;

class Kid extends Dad
{
    static $booted = 'no';

    public function boot()
    {
        //blagg

        static::$booted = 'yes';
    }

    public function parent()
    {
        echo parent::$booted;
    }
} 