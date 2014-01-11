<?php
/**
 * User: Andy
 * Date: 04/01/2014
 * Time: 16:36
 */

namespace AVCMS\Games\Controller;


use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GenerateController extends Controller {
    public function generateAction($table) {
        $pdo = $this->container->get('query_builder')->getQueryBuilder()->pdo();
        /**
         * @var $pdo \PDO
         */
        $q = $pdo->query("SHOW COLUMNS FROM $table");

        while ($col = $q->fetch()) {

            echo 'public function set'.$this->dashesToCamelCase($col['Field'], true).'($value) {';
            echo '<br />';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;$this->setData(\''.$col['Field'].'\', $value);';
            echo '<br />';
            echo '}<br/><br/>';

            echo 'public function get'.$this->dashesToCamelCase($col['Field'], true).'() {';
            echo '<br />';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;return $this->data(\''.$col['Field'].'\');';
            echo '<br />';
            echo '}<br/><br/>';

        }

        return new Response('');
    }

    protected function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {

        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
} 