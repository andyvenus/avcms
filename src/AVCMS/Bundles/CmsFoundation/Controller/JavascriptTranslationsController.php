<?php
/**
 * User: Andy
 * Date: 13/11/14
 * Time: 15:26
 */

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class JavascriptTranslationsController extends Controller
{
    public function javascriptTranslationsAction()
    {
        $translator = $this->container->get('translator');

        try {
            $translations = $translator->getMessages('javascript');
        }
        catch (NotFoundResourceException $e) {
            $translations = [];
        }

        return new Response("avcms.translations = ".json_encode($translations), 200, ['Content-Type' => 'text/javascript']);
    }
} 