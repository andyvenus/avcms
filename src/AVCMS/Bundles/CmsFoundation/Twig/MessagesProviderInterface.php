<?php
/**
 * User: Andy
 * Date: 06/05/15
 * Time: 13:16
 */

namespace AVCMS\Bundles\CmsFoundation\Twig;

interface MessagesProviderInterface
{
    /**
     * @return array|null
     */
    public function getMessages();
}
