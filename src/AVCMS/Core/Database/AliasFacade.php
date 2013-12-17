<?php

namespace AVCMS\Core\Database;

use Pixie\AliasFacade as PixieAliasFacade;

use AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler;

class AliasFacade extends PixieAliasFacade {

    public static function __callStatic($method, $args)
    {
        if (!static::$queryBuilderInstance) {
            static::$queryBuilderInstance = new QueryBuilderHandler();
        }

        // Call the non-static method from the class instance
        return call_user_func_array(array(static::$queryBuilderInstance, $method), $args);
    }

} 