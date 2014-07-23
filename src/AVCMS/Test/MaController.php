<?php
/**
 * User: Andy
 * Date: 17/07/2014
 * Time: 12:46
 */

namespace AVCMS\Test;

use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MaController extends Controller
{
    public function action()
    {
        $dad = new Dad();
        $kid = new Kid();

        $dad->boot();

        $st = 'dad: '.$dad->get();
        $st .= ' kid: '.$kid->get();

        return new Response($st);
    }
} 