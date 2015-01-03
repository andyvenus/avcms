<?php
/**
 * User: Andy
 * Date: 03/01/15
 * Time: 18:00
 */

namespace AVCMS\Bundles\Wallpapers\Form;

use AV\Form\ChoicesProviderInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RecursiveDirectoryChoicesProvider implements ChoicesProviderInterface
{
    private $directory;

    private $returnFullPath;

    public function __construct($directory, $returnFullPath = true)
    {
        $this->directory = $directory;
        $this->returnFullPath = $returnFullPath;
    }

    /**
     * @return array
     */
    public function getChoices()
    {
        $dirs = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->directory),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        $options = [];

        foreach ($dirs as $file) {
            if (in_array($file->getBasename(), array('.', '..'))) {
                continue;
            }

            if ($file->isDir() === false) {
                continue;
            }

            $folderPath = $file->getPath().'/'.$file->getFilename();
            $shortPath = str_replace($this->directory.'/', '', $folderPath);
            if ($this->returnFullPath === false) {
                $folderPath = $shortPath;
            }

            $options[$folderPath] = $shortPath;
        }

        return $options;
    }
}
