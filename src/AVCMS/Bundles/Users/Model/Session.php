<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 14:51
 */

namespace AVCMS\Bundles\Users\Model;


use AVCMS\Core\Model\Entity;

class Session extends Entity {
    public function setSessionId($value)
    {
        $this->set('session_id', $value);
    }

    public function getSessionId() {
        return $this->get('session_id');
    }

    public function setUserId($value) {
        $this->set('user_id', $value);
    }

    public function getUserId() {
        return $this->get('user_id');
    }

    public function setCsrf($value) {
        $this->set('csrf', $value);
    }

    public function getCsrf() {
        return $this->get('csrf');
    }

    public function setGenerated($value) {
        $this->set('generated', $value);
    }

    public function getGenerated() {
        return $this->get('generated');
    }
} 