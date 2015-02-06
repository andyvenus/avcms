<?php
/**
 * User: Andy
 * Date: 09/01/15
 * Time: 11:14
 */

namespace AVCMS\Bundles\Wallpapers\Form;

use AVCMS\Bundles\Categories\Form\CategoryAdminForm;
use AVCMS\Bundles\Categories\Model\Categories;

class WallpaperCategoryAdminForm extends CategoryAdminForm
{
    public function __construct($itemId, Categories $categories)
    {
        parent::__construct($itemId, $categories);

        $this->add('description', 'textarea', [
            'label' => 'Description'
        ]);
    }
}
