<?php
/**
 * User: Andy
 * Date: 04/11/14
 * Time: 14:20
 */

namespace AVCMS\Bundles\Reports\Form;

use AV\Form\FormBlueprint;

class ReportForm extends FormBlueprint
{
    public function __construct($contentType = null, $contentId = null)
    {
        $this->add('message', 'textarea', [
            'label' => 'Info',
            'required' => true
        ]);

        $this->add('content_type', 'hidden', [
            'default' => $contentType,
            'required' => true
        ]);

        $this->add('content_id', 'hidden', [
            'default' => $contentId,
            'required' => true
        ]);
    }
} 