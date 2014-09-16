<?php
/**
 * User: Andy
 * Date: 14/06/2014
 * Time: 19:52
 */

namespace AVCMS\Core\SlugGenerator;

class SlugGenerator
{
    public function generate($slugInput)
    {
        $slugOutput = stripslashes($slugInput);
        $slugOutput = strtolower($slugOutput);
        $slugOutput = str_replace("&", "and", $slugOutput);
        $slugOutput = str_replace(" ", "-", $slugOutput);
        $slugOutput = preg_replace("/[\.,\";<>@#%&?\/'\:]/", "", $slugOutput);
        $slugOutput = str_replace("---", "-", $slugOutput);
        $slugOutput = str_replace("--", "-", $slugOutput);

        return $slugOutput;
    }
} 