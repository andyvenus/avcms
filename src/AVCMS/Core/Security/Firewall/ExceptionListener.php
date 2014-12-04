<?php
/**
 * User: Andy
 * Date: 03/12/14
 * Time: 15:51
 */

namespace AVCMS\Core\Security\Firewall;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\ExceptionListener as BaseExceptionListener;

class ExceptionListener extends BaseExceptionListener
{
    public function setTargetPath(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return;
        }

        $ext = pathinfo($request->getUri(), PATHINFO_EXTENSION);

        if ($ext === 'js' || $ext === 'css') {
            return;
        }

        if ($request->attributes->get('_route') === 'login') {
            return;
        }

        parent::setTargetPath($request);
    }
} 