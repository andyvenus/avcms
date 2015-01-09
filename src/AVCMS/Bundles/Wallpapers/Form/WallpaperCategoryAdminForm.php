<?php
/**
 * User: Andy
 * Date: 09/01/15
 * Time: 11:14
 */

namespace AVCMS\Bundles\Wallpapers\Form;

use AVCMS\Bundles\Categories\Form\CategoryAdminForm;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;

class WallpaperCategoryAdminForm extends CategoryAdminForm
{
    public function __construct(CategoryChoicesProvider $categoryChoicesProvider)
    {
        parent::__construct($categoryChoicesProvider);

        $this->add('description', 'textarea', [
            'label' => 'Description'
        ]);
    }
}
