<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 13:54
 */

namespace AVCMS\Bundles\Games\Form;

use AVCMS\Bundles\Categories\Form\CategoryAdminForm;
use AVCMS\Bundles\Categories\Model\Categories;

class GamesCategoryAdminForm extends CategoryAdminForm
{
    public function __construct($itemId, Categories $categories)
    {
        parent::__construct($itemId, $categories);

        $this->add('description', 'textarea', [
            'label' => 'Description'
        ]);
    }
}
