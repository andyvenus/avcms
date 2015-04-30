<?php
/**
 * User: Andy
 * Date: 24/12/2013
 * Time: 20:29
 */

namespace AVCMS\Core\Translation;

use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator as TranslatorBase;

// todo: Admin translations
class Translator extends TranslatorBase
{

    protected $translatedStrings = array();

    protected $debug;

    private $selector;

    public function __construct(SettingsManager $settings, MessageSelector $selector = null, $debug = false, RequestStack $requestStack = null)
    {
        $locale = $settings->getSetting('language');

        if ($requestStack && strpos($requestStack->getMasterRequest()->getUri(), 'admin') !== false) {
            $locale = null;
        }

        if (!$locale) {
            $locale = 'en';
        }

        $this->debug = $debug;
        $this->selector = $selector ?: new MessageSelector();

        parent::__construct($locale, $selector);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        if (null === $locale) {
            $locale = $this->getLocale();
        }

        if (null === $domain) {
            $domain = 'messages';
        }

        if (!isset($this->catalogues[$locale])) {
            $this->loadCatalogue($locale);
        }

        $params = array();
        foreach ($parameters as $placeholder => $value) {
            $params['{'.$placeholder.'}'] = $value;
        }

        $rawTranslation = $this->catalogues[$locale]->get((string) $id, $domain);
        $translation = strtr($rawTranslation, $params);

        if ($this->debug === true) {
            $this->translatedStrings[$id] = array(
                'raw' => $id,
                'translation' => $translation,
                'raw_translation' => $rawTranslation,
                'parameters' => $parameters
            );
        }

        return $translation;
    }

    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        $translation = parent::transChoice($id, $number, $parameters, $domain, $locale);

        if ($this->debug === true) {
            if (null === $locale) {
                $locale = $this->getLocale();
            }

            $catalogue = $this->catalogues[$locale];

            $rawTranslation = $catalogue->get($id, $domain);

            $this->translatedStrings[$id] = array(
                'raw' => $id,
                'translation' => 'Multiple',
                'raw_translation' => $rawTranslation,
                'parameters' => $parameters
            );
        }

        return $translation;
    }

    public function getTranslationStrings()
    {
        return $this->translatedStrings;
    }

    public function getMessages($domain = null, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->getLocale();
        }

        if ($domain === null) {
            $domain = 'messages';
        }

        if (!isset($this->catalogues[$locale])) {
            $this->loadCatalogue($locale);
        }

        return $this->catalogues[$locale]->all($domain);
    }

    public function loadTranslationsFromDirs($format, $dir, $fileExtension)
    {
        if (!file_exists($dir)) {
            return;
        }

        $langFolders = new \DirectoryIterator($dir);
        foreach ($langFolders as $langFolder) {
            if ($langFolder->isDot()) {
                continue;
            }

            if ($langFolder->isDir()) {
                $this->loadTranslationsFromDir($format, $langFolder, $fileExtension);
            }

            if (file_exists($langFolder->getRealPath().'/javascript')) {
                $this->loadTranslationsFromDir($format, new \SplFileInfo($langFolder->getRealPath().'/javascript'), $fileExtension, 'javascript', $langFolder->getFilename());
            }
        }
    }

    protected function loadTranslationsFromDir($format, \SplFileInfo $langFolder, $fileExtension, $domain = null, $locale = null)
    {
        if ($locale === null) {
            $locale = $langFolder->getFilename();
        }

        $translations = new \DirectoryIterator($langFolder->getRealPath());
        foreach ($translations as $translation) {
            if ($translation->getExtension() == $fileExtension) {
                $this->addResource($format, $translation->getRealPath(), $locale, $domain);
            }
        }
    }
}
