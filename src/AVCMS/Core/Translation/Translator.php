<?php
/**
 * User: Andy
 * Date: 24/12/2013
 * Time: 20:29
 */

namespace AVCMS\Core\Translation;

use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator as TranslatorBase;

class Translator extends TranslatorBase
{

    protected $translated_strings = array();

    protected $debug;

    public function __construct(SettingsManager $settings, MessageSelector $selector = null, $debug = false)
    {
        $locale = $settings->getSetting('language');
        if (!$locale) {
            $locale = 'en';
        }

        $this->debug = $debug;

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

        $raw_translation = $this->catalogues[$locale]->get((string) $id, $domain);
        $translation = strtr($raw_translation, $params);

        if ($this->debug === true) {
            $this->translated_strings[$id] = array(
                'raw' => $id,
                'translation' => $translation,
                'raw_translation' => $raw_translation,
                'parameters' => $parameters
            );
        }

        return $translation;
    }

    public function getTranslationStrings()
    {
        return $this->translated_strings;
    }

    public function loadTranslationsFromDir($format, $dir, $fileExtension)
    {
        $langFolders = new \DirectoryIterator($dir);
        foreach ($langFolders as $langFolder) {
            if ($langFolder->isDot()) {
                continue;
            }

            if ($langFolder->isDir()) {
                $translations = new \DirectoryIterator($langFolder->getRealPath());
                foreach ($translations as $translation) {
                    if ($translation->getExtension() == $fileExtension) {
                        $this->addResource($format, $translation->getRealPath(), $langFolder->getFilename());
                    }
                }
            }
        }

    }
} 