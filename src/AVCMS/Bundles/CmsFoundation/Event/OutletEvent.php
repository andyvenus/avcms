<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 10:46
 */

namespace AVCMS\Bundles\CmsFoundation\Event;

use Symfony\Component\EventDispatcher\Event;

class OutletEvent extends Event
{
    private $outletName;

    private $content = '';

    public function __construct($outletName)
    {
        $this->outletName = $outletName;
    }

    public function getOutletName()
    {
        return $this->outletName;
    }

    public function addContent($content)
    {
        $this->content .= $content;
    }

    public function getContent()
    {
        return $this->content;
    }
}
