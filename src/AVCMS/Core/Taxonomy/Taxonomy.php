<?php
/**
 * User: Andy
 * Date: 25/06/2014
 * Time: 13:01
 */

namespace AVCMS\Core\Taxonomy;


interface Taxonomy {
    public function getModel();

    public function getRelationsModel();

    public function update($content_id, $content_type, array $tags);
} 