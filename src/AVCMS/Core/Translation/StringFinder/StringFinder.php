<?php
/**
 * User: Andy
 * Date: 12/10/2014
 * Time: 13:52
 */

namespace AVCMS\Core\Translation\StringFinder;

class StringFinder
{
    public function getResults($string, $path)
    {
        $string = trim($string);

        // We can't handle quotes :/
        if (false !== strpos($string, '"')) {
            return null;
        }

        $stringVariants = [];

        if (str_word_count($string) > 1) {
            $stringVariants[] = $string;
        }
        else {
            $stringVariants[] = "'{$string}'";
            $stringVariants[] = "{% trans %}$string{% endtrans %}";
            $stringVariants[] = ': '.$string;
        }

        $pattern = implode('\|', $stringVariants);

        $command = 'grep -nr "'.$pattern.'" '.$path.' --exclude="*.txt"';

        $output = array();
        exec($command, $output);

        $formatted = [];
        foreach ($output as $match) {
            $match = explode(':', $match);

            if (count($match) !== 1) {
                $formatted[] = ['file' => $match[0], 'line_no' => $match[1], 'line' => trim($match[2])];
            }
        }

        return $formatted;
    }

}
