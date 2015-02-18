<?php
/**
 * User: Andy
 * Date: 18/02/15
 * Time: 19:24
 */

namespace AVCMS\Bundles\Referrals\Controller;

use Symfony\Component\HttpFoundation\Response;

class ShareModuleController
{
    public function shareModule()
    {
        return new Response(file_get_contents(__DIR__.'/../resources/templates/module/share_module.php'));
    }
}
