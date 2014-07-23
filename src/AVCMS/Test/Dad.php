<?php
/**
 * User: Andy
 * Date: 17/07/2014
 * Time: 12:38
 */

namespace AVCMS\Test;

class Dad
{
    static $booted = 'no';

    public function boot()
    {
        //blagg

        static::$booted = 'yes';
    }

    public function get()
    {
        return static::$booted;
    }
}