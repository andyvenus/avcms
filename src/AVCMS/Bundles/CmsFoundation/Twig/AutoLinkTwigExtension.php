<?php
/**
Copyright (c) 2011 Liip AG

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
 */

namespace AVCMS\Bundles\CmsFoundation\Twig;

class AutoLinkTwigExtension extends \Twig_Extension
{
    protected $linkClass;
    protected $target = '_blank';
    protected $debugMode;
    protected $debugColor = '#00ff00';

    public function getName()
    {
        return 'liip_urlautoconverter';
    }

    public function setLinkClass($class)
    {
        $this->linkClass = $class;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

    public function setDebugMode($debug)
    {
        $this->debugMode = $debug;
    }

    public function setDebugColor($color)
    {
        $this->debugColor = $color;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'link_text',
                array($this, 'autoConvertUrls'),
                array(
                    'pre_escape' => 'html',
                    'is_safe' => array('html'),
                )
            ),
        );
    }

    /**
     * method that finds different occurrences of urls or email addresses in a string.
     *
     * @param string $string input string
     *
     * @return string with replaced links
     */
    public function autoConvertUrls($string)
    {
        $pattern = '/(href="|src=")?([-a-zA-Zа-яёА-ЯЁ0-9@:%_\+.~#?&\/\/=]{2,256}\.[a-zа-яё]{2,4}\b(\/?[-\p{L}0-9@:%_\+.~#?&\/\/=\(\),]*)?)/u';
        $stringFiltered = preg_replace_callback($pattern, array($this, 'callbackReplace'), $string);
        return $stringFiltered;
    }

    public function callbackReplace($matches)
    {
        if ($matches[1] !== '') {
            return $matches[0]; // don't modify existing <a href="">links</a> and <img src="">
        }
        $url = $matches[2];
        $urlWithPrefix = $matches[2];
        if (strpos($url, '@') !== false) {
            $urlWithPrefix = 'mailto:'.$url;
        } elseif (strpos($url, 'https://') === 0) {
            $urlWithPrefix = $url;
        } elseif (strpos($url, 'http://') !== 0) {
            $urlWithPrefix = 'http://'.$url;
        }
        $style = ($this->debugMode) ? ' style="color:'.$this->debugColor.'"' : '';
        // ignore tailing special characters
        // TODO: likely this could be skipped entirely with some more tweakes to the regular expression
        if (preg_match("/^(.*)(\.|\,|\?)$/", $urlWithPrefix, $matches)) {
            $urlWithPrefix = $matches[1];
            $url = substr($url, 0, -1);
            $punctuation = $matches[2];
        } else {
            $punctuation = '';
        }
        return '<a href="'.$urlWithPrefix.'" class="'.$this->linkClass.'" target="'.$this->target.'"'.$style.'>'.$url.'</a>'.$punctuation;
    }
}
