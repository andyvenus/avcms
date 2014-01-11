<?php
/**
 * User: Andy
 * Date: 24/12/2013
 * Time: 20:29
 */

namespace AVCMS\Core\Translation;

use Symfony\Component\Translation\Translator as TranslatorBase;

class Translator extends TranslatorBase
{
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

        return strtr($this->catalogues[$locale]->get((string) $id, $domain), $params);
    }
} 