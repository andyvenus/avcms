<?php
/**
 * User: Andy
 * Date: 03/07/2014
 * Time: 11:16
 */

namespace AVCMS\Fields\Slug;

use AVCMS\Core\Taxonomy\TaxonomyManager;
use AVCMS\Fields\ContentField;

class Slug extends ContentField
{
    /**
     * @var string|null
     */
    protected $tasks = null;

    /**
     * @var string
     */
    protected $form = 'AVCMS\Fields\Slug\SlugForm';

    public function getSchemaDefaults()
    {
        return array(
            'name' => 'slug',
            'type' => 'varchar',
            'length' => 255
        );
	}
}