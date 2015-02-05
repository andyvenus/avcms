<?php
/**
 * User: Andy
 * Date: 05/02/15
 * Time: 16:19
 */

namespace AVCMS\BundlesDev\DevTools\Controller;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MiscToolsController extends Controller
{
    public function twigNamespacesForPhpStormAction()
    {
        $configs = $this->get('bundle_manager')->getBundleConfigs();
        $response = '';
        foreach ($configs as $c) {
            $response .= htmlspecialchars('<twig_namespace custom="true" namespace="'.$c->name.'" namespaceType="ADD_PATH" path="'.$c->directory.'/resources/templates" />').'<br>';
        }

        return new Response($response, 200);
    }
}
