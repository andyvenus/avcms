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
    /**
     * @var string
     */
    private $outletName;

    /**
     * @var string
     */
    private $content = '';

    /**
     * @var array
     */
    private $vars;

    public function __construct($outletName, array $vars)
    {
        $this->outletName = $outletName;
        $this->vars = $vars;
    }

    /**
     * @return string
     */
    public function getOutletName()
    {
        return $this->outletName;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @param $varName
     * @return mixed
     */
    public function getVar($varName)
    {
        return isset($this->vars[$varName]) ? $this->vars[$varName] : null;
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
