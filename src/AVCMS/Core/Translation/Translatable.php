<?php
/**
 * User: Andy
 * Date: 22/12/2013
 * Time: 23:02
 */

namespace AVCMS\Core\Translation;


use Symfony\Component\Translation\TranslatorInterface;

interface Translatable {
    public function setTranslator(TranslatorInterface $translator);
} 