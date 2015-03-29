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
        $this->saveStringsAction($request);

        $rootDir = $this->getParam('root_dir');
        $bundle = $request->get('bundle');
        $bundleConfig = $this->container->get('bundle_manager')->getBundleConfig($bundle);
        $bundleDir = $rootDir.'/'.$bundleConfig->directory;

        $file = $rootDir.'/dev/'.$bundle.'/translation_strings.txt';

        if (!file_exists($file)) {
            throw $this->createNotFoundException('No translation strings found');
        }

        $foundStrings = unserialize(file_get_contents($file));


        $usedTranslationStrings = [];
        $unusedTranslationStrings = [];
        $translatedElsewhereStrings = [];

        $previouslyAccepted = [];
        if (file_exists($bundleDir.'/resources/translations/strings.txt')) {
            $previouslyAcceptedFile = file($bundleDir . '/resources/translations/strings.txt');
            foreach ($previouslyAcceptedFile as $fileString) {
                $previouslyAccepted[] = trim($fileString);
            }
        }

        if (file_exists($bundleDir.'/resources/translations/strings-admin.txt')) {
            $previouslyAcceptedFile = file($bundleDir . '/resources/translations/strings-admin.txt');
            foreach ($previouslyAcceptedFile as $fileString) {
                $previouslyAccepted[] = trim($fileString);
            }
        }

        $allStrings = [];
        $allBundles = [];
        $allBundleConfigs = $this->container->get('bundle_manager')->getBundleConfigs();
        foreach ($allBundleConfigs as $otherBundleConfig) {
            if (file_exists($otherBundleConfig->directory.'/resources/translations/strings.txt')) {
                $previouslyAcceptedFile = file($otherBundleConfig->directory . '/resources/translations/strings.txt');
                foreach ($previouslyAcceptedFile as $fileString) {
                    $allStrings[trim($fileString)] = $otherBundleConfig->name;
                }
            }

            if (file_exists($otherBundleConfig->directory.'/resources/translations/strings-admin.txt')) {
                $previouslyAcceptedFile = file($otherBundleConfig->directory . '/resources/translations/strings-admin.txt');
                foreach ($previouslyAcceptedFile as $fileString) {
                    $allStrings[trim($fileString)] = $otherBundleConfig->name;
                }
            }

            $allBundles[] = $otherBundleConfig->name;
        }

        $stringFinder = new StringFinder();

        foreach ($foundStrings as $string) {
            if (in_array($string, $previouslyAccepted)) {
                continue;
            }

            $usages = $stringFinder->getResults($string, $bundleDir);

            $translationInfo = ['string' => $string, 'usages' => $usages];

            if ($usages === null) {
                $translationInfo['string'] .= ' - String contains quotations so cannot be validated';
                $translationInfo['usages'] = [];
            }

            if (count($usages) > 0 && ((isset($allStrings[$string]) && !in_array($allStrings[$string], ['CmsFoundation', 'Admin'])) || !isset($allStrings[$string]))) {
                $usedTranslationStrings[$string] = $translationInfo;
            }
            elseif (isset($allStrings[$string])) {
                $translationInfo['bundle'] = $allStrings[$string];
                $translationInfo['usages'] = [];
                $translatedElsewhereStrings[$string] = $translationInfo;
            }
            else {
                $unusedTranslationStrings[$string] = $translationInfo;
            }
        }

        $translationStrings = $usedTranslationStrings + $unusedTranslationStrings + $translatedElsewhereStrings;

        return new Response($this->render('@DevTools/review_translations.twig', ['translation_strings' => $translationStrings, 'all_bundles' => $allBundles]));
    }

    public function saveStringsAction(Request $request)
    {
        $rootDir = $this->getParam('root_dir');
        $bundle = $request->get('bundle');
        $bundleConfig = $this->container->get('bundle_manager')->getBundleConfig($bundle);
        $bundleDir = $rootDir.'/'.$bundleConfig->directory;

        if ($request->request->has('translations')) {
            $strings = $request->request->get('translations');

            $stringsFrontend = [];
            if (file_exists($bundleDir.'/resources/translations/strings.txt')) {
                $previouslyAcceptedFile = file($bundleDir . '/resources/translations/strings.txt');
                foreach ($previouslyAcceptedFile as $fileString) {
                    $stringsFrontend[] = trim($fileString);
                }
            }

            $stringsAdmin = [];
            if (file_exists($bundleDir.'/resources/translations/strings-admin.txt')) {
                $previouslyAcceptedFile = file($bundleDir . '/resources/translations/strings-admin.txt');
                foreach ($previouslyAcceptedFile as $fileString) {
                    $stringsAdmin[] = trim($fileString);
                }
            }

            $stringFinder = new StringFinder();

            foreach ($strings as $string) {
                $results = $stringFinder->getResults($string, $bundleDir);

                if (!$results) {
                    $results = [];
                }

                $admin = true;
                foreach ($results as $result) {
                    if (stripos($result['file'], 'admin') === false && stripos($result['file'], '.yml') === false) {
                        $admin = false;
                        break;
                    }
                }

                if ($admin) {
                    if (!in_array($string, $stringsAdmin)) {
                        $stringsAdmin[] = $string;
                    }
                }
                else {
                    if (!in_array($string, $stringsFrontend)) {
                        $stringsFrontend[] = $string;
                    }
                }
            }

            if (!file_exists($bundleDir.'/resources/translations')) {
                mkdir($bundleDir.'/resources/translations', 0777, true);
            }

            if (!empty($stringsFrontend)) {
                file_put_contents($bundleDir . '/resources/translations/strings.txt', implode(PHP_EOL, $stringsFrontend));
            }
            if (!empty($stringsAdmin)) {
                file_put_contents($bundleDir . '/resources/translations/strings-admin.txt', implode(PHP_EOL, $stringsAdmin));
            }
        }

        return new Response('');
    }

    public function splitterAction()
    {
        $configs = $this->get('bundle_manager')->getBundleConfigs();
        $stringFinder = new StringFinder();

       foreach ($configs as $config) {

           $adminTranslations = [];
           $frontendTranslations = [];

           if (file_exists($file = $config['directory'] . '/resources/translations/strings.txt')) {
               $strings = file($file);

               foreach ($strings as $string) {
                   $results = $stringFinder->getResults($string, $config['directory']);

                   if (!$results) {
                       $results = [];
                   }

                   $admin = true;
                   foreach ($results as $result) {
                       if (stripos($result['file'], 'admin') === false && stripos($result['file'], '.yml') === false) {
                           $admin = false;
                           break;
                       }
                   }

                   if ($admin == true) {
                       $adminTranslations[] = trim($string);
                   } else {
                       $frontendTranslations[] = trim($string);
                   }
               }

               if (!empty($frontendTranslations)) {
                   file_put_contents($config['directory'] . '/resources/translations/strings.txt', implode(PHP_EOL, $frontendTranslations));
               } else {
                   unlink($config['directory'] . '/resources/translations/strings.txt');
               }

               if (!empty($adminTranslations)) {
                   file_put_contents($config['directory'] . '/resources/translations/strings-admin.txt', implode(PHP_EOL, $adminTranslations));
               }
           }
       }

        return new Response('k');
    }
}
