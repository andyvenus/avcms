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
    public function getChoices()
    {
        $choices = ['en' => 'Default (English)'];
        $langFolders = new \DirectoryIterator('webmaster/translations');
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