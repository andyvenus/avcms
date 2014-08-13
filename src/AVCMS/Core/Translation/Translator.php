<?php
/**
 * User: Andy
 * Date: 24/12/2013
 * Time: 20:29
 */

namespace AVCMS\Core\Translation;

use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator as TranslatorBase;

class Translator extends TranslatorBase
{

    protected $translated_strings = array();

    protected $debug;

    public function __construct($locale, MessageSelector $selector = null, $debug = false)
    {
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
} 