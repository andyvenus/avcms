<?php
/**
 * User: Andy
 * Date: 02/01/2014
 * Time: 20:09
 */

namespace AVCMS\Core\Model\Tests\Fixtures;

use AVCMS\Core\Model\Model;

class Animals extends Model
{
    protected $table = 'animals';

    protected $singular = 'animals';

    protected $entity = 'AVCMS\Core\Model\Tests\Fixtures\Animal';
}