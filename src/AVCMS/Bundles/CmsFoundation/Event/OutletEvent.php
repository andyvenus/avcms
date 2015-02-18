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

    private $vars;

    public function __construct($outletName, $vars)
    {
        $this->outletName = $outletName;
        $this->vars = $vars;
    }

    public function getOutletName()
    {
        return $this->outletName;
    }

    public function getVars()
    {
        return $this->vars;
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
