<?php
/**
 * User: Andy
 * Date: 25/11/14
 * Time: 20:26
 */

namespace AVCMS\Bundles\CmsFoundation\Controller;

use AV\Form\FormBlueprint;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ClearCachesController extends Controller
{
    public function clearCachesAction(Request $request)
    {
        if (!$this->isGranted('ADMIN_CLEAR_CACHES')) {
            throw new AccessDeniedException;
        }

        $formBlueprint = new FormBlueprint();
        $formBlueprint->setAction($this->generateUrl('clear_caches', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $formBlueprint->add("cache_dirs[]", 'checkbox', ['label' => 'Core Caches', 'checked_value' => 'main']);

        foreach (new \DirectoryIterator($this->getParam('cache_dir')) as $dir) {
            if ($dir->isDir() && !$dir->isDot()) {
                $formBlueprint->add("cache_dirs[]", 'checkbox', ['label' => ucfirst($dir->getFilename()), 'checked_value' => $dir->getFilename()]);
            }
        }

        $form = $this->buildForm($formBlueprint, $request);

        if ($request->request->has('cache_dirs')) {
            $this->get('cache.clearer')->clearCaches($request->request->get('cache_dirs'));

            return new JsonResponse(['success' => true, 'form' => ['success' => true]]);
        }

        return new JsonResponse(['html' => $this->render('@CmsFoundation/modal_form.twig', ['form' => $form->createView(), 'modal_title' => 'Clear Caches'])]);
    }
} 
