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
        $formBlueprint->add("cache_dirs[main]", 'checkbox', ['label' => 'Core Caches']);

        foreach (new \DirectoryIterator($this->container->getParameter('cache_dir')) as $dir) {
            if ($dir->isDir() && !$dir->isDot()) {
                $formBlueprint->add("cache_dirs[{$dir->getFilename()}]", 'checkbox', ['label' => ucfirst($dir->getFilename())]);
            }
        }

        $form = $this->buildForm($formBlueprint, $request);

        if ($form->isSubmitted()) {
            $this->get('cache.clearer')->clearCaches(array_keys($form->getData('cache_dirs')));

            return new JsonResponse(['success' => true, 'form' => $form->createView()->getJsonResponseData()]);
        }

        return new JsonResponse(['html' => $this->render('@CmsFoundation/modal_form.twig', ['form' => $form->createView(), 'modal_title' => 'Clear Caches'])]);
    }
} 