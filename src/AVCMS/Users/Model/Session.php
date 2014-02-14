<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 14:51
 */

namespace AVCMS\Users\Model;


use AVCMS\Core\Model\Entity;

class Session extends Entity {
    public function setSessionId($value)
    {
        $this->setData('session_id', $value);
    }

    public function getSessionId() {
        return $this->data('session_id');
    }

    public function setUserId($value) {
        $this->setData('user_id', $value);
    }

    public function getUserId() {
        return $this->data('user_id');
    }

    public function setCsrf($value) {
        $this->setData('csrf', $value);
    }

    public function getCsrf() {
        return $this->data('csrf');
    }

    public function setGenerated($value) {
        $this->setData('generated', $value);
    }

    public function getGenerated() {
        return $this->data('generated');
    }
} 