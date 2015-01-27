<?php
/**
 * User: Andy
 * Date: 02/11/14
 * Time: 11:10
 */

namespace AVCMS\Bundles\CmsFoundation\Settings;

use AV\Form\ChoicesProviderInterface;

class LanguageChoicesProvider implements ChoicesProviderInterface
{
    private $dir = 'webmaster/translations';

    public function getChoices()
    {
        $choices = ['en' => 'Default (English)'];

        if (!file_exists($this->dir)) {
            return $choices;
        }

        $langFolders = new \DirectoryIterator($this->dir);
        foreach ($langFolders as $langFolder) {
            if ($langFolder->isDot()) {
                continue;
            }

            if ($langFolder->isDir()) {
                $displayName = $langFolder->getFilename();
                if (function_exists('locale_get_display_language')) {
                    $displayName = locale_get_display_language($langFolder->getFilename());
                }
                $choices[$langFolder->getFilename()] = $displayName;
            }
        }

        return $choices;
    }
}
