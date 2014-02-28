<?php
/**
 * User: Andy
 * Date: 17/12/2013
 * Time: 21:25
 */

namespace AVCMS\Core\View;


class TwigLoaderFilesystem extends \Twig_Loader_Filesystem {

    /**
     * @var array
     */
    protected $overrides;

    public function __construct($paths = array(), $overrides = array())
    {
        if ($paths) {
            $this->setPaths($paths);
        }

        if ($overrides) {
            $this->overrides = $overrides;
        }
    }

    public function setOverride($original, $replacement) {
        $this->overrides[$original] = $replacement;
    }

    protected function findTemplate($name)
    {
        if (isset($this->overrides[$name])) {
            $name = $this->overrides[$name];
        }

        return parent::findTemplate($name);
    }
} 