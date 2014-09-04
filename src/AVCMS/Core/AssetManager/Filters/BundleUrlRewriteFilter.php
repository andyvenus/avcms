<?php

namespace AVCMS\Core\AssetManager\Filters;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\BaseCssFilter;
use AVCMS\Core\AssetManager\Asset\BundleFileAsset;

class BundleUrlRewriteFilter extends BaseCssFilter
{
    public function filterLoad(AssetInterface $asset)
    {
    }

    public function filterDump(AssetInterface $asset)
    {
        if (!($asset instanceof BundleFileAsset)) {
            return;
        }

        $content = $this->filterReferences($asset->getContent(), function($matches) use ($asset) {
            if (false !== strpos($matches['url'], '://') || 0 === strpos($matches['url'], '//') || 0 === strpos($matches['url'], 'data:')) {
                return $matches[0];
            }

            if (isset($matches['url'][0]) && '/' == $matches['url'][0]) {
                return $matches[0];
            }

            return str_replace($matches['url'], '../resources/'.$asset->getBundle().'/'.$asset->getType().'/'.$matches['url'], $matches[0]);
        });

        $asset->setContent($content);
    }
}