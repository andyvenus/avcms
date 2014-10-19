<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 14:51
 */

namespace AVCMS\Bundles\Users\Model;


use AV\Model\Entity;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentTokenInterface;

class Session extends Entity implements PersistentTokenInterface
{
    public function getClass()
    {
        return $this->get("class");
    }

    public function setClass($value)
    {
        $this->set("class", $value);
    }

    public function getLastUsedTimestamp()
    {
        return $this->get("last_used_timestamp");
    }

    public function setLastUsedTimestamp($value)
    {
        $this->set("last_used_timestamp", $value);
    }

    public function getLastUsed()
    {
        $lastUsed = new \DateTime();
        $lastUsed->setTimestamp($this->get("last_used_timestamp"));

        return $lastUsed;
    }

    public function setLastUsed(\DateTime $dateTime)
    {
        $this->set("last_used_timestamp", $dateTime->getTimestamp());
    }

    public function setSeries($value)
    {
        $this->set("series", $value);
    }

    public function getSeries()
    {
        return $this->get("series");
    }

    public function getTokenValue()
    {
        return $this->get("token_value");
    }

    public function setTokenValue($value)
    {
        $this->set("token_value", $value);
    }

    public function getUsername()
    {
        return $this->get("username");
    }

    public function setUsername($value)
    {
        $this->set("username", $value);
    }
}