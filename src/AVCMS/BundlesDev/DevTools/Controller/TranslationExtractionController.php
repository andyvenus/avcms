<?php
/**
 * User: Andy
 * Date: 12/10/2014
 * Time: 14:39
 */

namespace AVCMS\BundlesDev\DevTools\Controller;

use AVCMS\Core\Controller\Controller;
use AVCMS\Core\Translation\StringFinder\StringFinder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslationExtractionController extends Controller
{
    public function reviewBundleTranslations(Request $request)
    {
        $rootDir = $this->container->getParameter('root_dir');
        $bundle = $request->get('bundle');
        $bundleConfig = $this->container->get('bundle_manager')->getBundleConfig($bundle);
        $bundleDir = $rootDir.'/'.$bundleConfig->directory;

        $file = $rootDir.'/dev/'.$bundle.'/translation_strings.txt';

        if (!file_exists($file)) {
            throw $this->createNotFoundException('No translation strings found');
        }

        $foundStrings = unserialize(file_get_contents($file));

        $stringFinder = new StringFinder();

        $translationStrings = [];
        foreach ($foundStrings as $string) {
            $usages = $stringFinder->getResults($string, $bundleDir);

            if ($usages === null) {
                $translationStrings[$string . ' - String contains quotations so cannot be validated'] = '1';
            }
            else {
                $translationStrings[$string] = $usages;
            }
        }

        return new Response($this->render('@DevTools/review_translations.twig', ['translation_strings' => $translationStrings]));
    }
}