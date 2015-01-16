<?php
/**
 * User: Andy
 * Date: 16/01/15
 * Time: 15:49
 */

namespace AVCMS\Core\Kernel;

use Symfony\Component\HttpFoundation\RequestStack;

class SiteRootUrl
{
    private $siteUrl;

    public function __construct(RequestStack $requestStack)
    {
        $url = $requestStack->getMasterRequest()->getUriForPath('/');

        $basename = basename($url);

        if (strpos($basename, '.php') !== false) {
            $url = str_replace($basename, '', $url);
        }

        $this->siteUrl = $url;
    }

    public function getSiteUrl()
    {
        return $this->siteUrl;
    }
}
