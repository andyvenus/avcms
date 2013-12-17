<?php

namespace AVCMS\Games\View;

use AVCMS\Core\View\TemplateObject;

class TemplateGame extends TemplateObject  {
        public function getHTMLVars() {
            return array('embed_code');
        }
}