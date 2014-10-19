<?php
/**
 * User: Andy
 * Date: 12/10/2014
 * Time: 13:31
 */

namespace AVCMS\BundlesDev\DevTools\Listener;

use AV\Kernel\Bundle\BundleManagerInterface;
use AVCMS\Core\Translation\Translator;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

class BundleTranslationsCollector
{
    protected $debug;

    protected $translator;

    protected $requestStack;

    protected $bundleManager;

    protected $rootDir;

    public function __construct(Translator $translator, BundleManagerInterface $bundleManager, $rootDir = null, $debug = false)
    {
        $this->debug = $debug;
        $this->translator = $translator;
        $this->bundleManager = $bundleManager;
        $this->rootDir = $rootDir;
    }

    public function onTerminate(PostResponseEvent $event)
    {
        if (!$bundle = $event->getRequest()->attributes->get('_bundle')) {
            return;
        }

        $config = $this->bundleManager->getBundleConfig($bundle);
        $dir = $this->rootDir.'/dev/'.$bundle;

        $file = $dir.'/translation_strings.txt';

        if (file_exists($file)) {
            $usedStrings = unserialize(file_get_contents($file));
        }
        else {
            $usedStrings = [];
        }

        foreach ($this->translator->getTranslationStrings() as $translation) {
            $usedStrings[] = $translation['raw'];
        }

        $usedStrings = array_unique($usedStrings);

        if (!empty($usedStrings)) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            file_put_contents($file, serialize($usedStrings));
        }
    }
}