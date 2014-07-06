<?php
/**
 * User: Andy
 * Date: 14/06/2014
 * Time: 19:52
 */

namespace AVCMS\Core\SlugGenerator;

class SlugGenerator
{
    public function generate($slug_input)
    {
        $slug_output = stripslashes($slug_input);
        $slug_output = strtolower($slug_output);
        $slug_output = str_replace("&", "and", $slug_output);
        $slug_output = str_replace(" ", "-", $slug_output);
        $slug_output = preg_replace("/[\.,\";<>@#%&?\/'\:]/", "", $slug_output);
        $slug_output = str_replace("---", "-", $slug_output);
        $slug_output = str_replace("--", "-", $slug_output);

        return $slug_output;
    }
} 