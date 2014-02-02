<?php
/**
 * User: Andy
 * Date: 16/01/2014
 * Time: 15:19
 */

namespace AVCMS\Core\Form\Twig;


class FormTest extends \Twig_Node {
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('$context[\''.$this->getAttribute('name').'\'] = ')
            ->subcompile($this->getNode('value'))
            ->raw(";\n")
        ;
    }

    public function getSafe($something)
    {
        return 'testxxx';
    }
} 