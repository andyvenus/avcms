<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 15:26
 */

namespace AVCMS\Users\Model;

use AVCMS\Core\Model\Model;

class Sessions extends Model
{
    public function getTable()
    {
        return 'sessions';
    }

    public function getSingular()
    {
        return 'session';
    }

    public function getEntity()
    {
        return 'AVCMS\Users\Model\Session';
    }

    public function generateAndSaveSession($user_id)
    {
        $entity = $this->getEntity();
        $session = new $entity;

        $session->setSessionId(bin2hex(openssl_random_pseudo_bytes(40)));
        $session->setUserId($user_id);
        $session->setGenerated(time());

        $this->save($session);

        return $session;
    }

    public function getSession($session_id, $user_id)
    {
        return $this->query()->where('session_id', $session_id)->where('user_id', $user_id)->first();
    }
} 