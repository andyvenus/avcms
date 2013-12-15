<?php

namespace Games\View;

use AVCMS\View\TemplateObject;

class TemplateGame extends TemplateObject  {
        public function getHTMLVars() {
            return array('embed_code');
        }
}