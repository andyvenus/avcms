<?php
/**
 * User: Andy
 * Date: 03/01/15
 * Time: 12:47
 */

namespace AVCMS\Bundles\BulkUpload\Form;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;

class BulkImportAdminFiltersForm extends AdminFiltersForm
{
    function __construct()
    {
        parent::__construct();

        $this->add('show_single_char_dirs', 'checkbox', [
            'label' => 'Show Single Character Dirs'
        ]);
    }
}
