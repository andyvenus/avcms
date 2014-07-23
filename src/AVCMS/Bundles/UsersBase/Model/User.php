<?php
/**
 * User: Andy
 * Date: 09/02/2014
 * Time: 19:16
 */

namespace AVCMS\Bundles\UsersBase\Model;

use AVCMS\Core\Model\Entity;


class User extends Entity
{
    /**
     * @param $value
     */
    public function setId($value) {
        $this->set('id', $value);
    }

    /**
     * @return null
     */
    public function getId() {
        return $this->get('id');
    }

    public function setUsername($value) {
        $this->set('username', $value);
    }

    public function getUsername() {
        return $this->get('username');
    }

    public function setPassword($value) {
        $this->set('password', $value);
    }

    public function getPassword() {
        return $this->get('password');
    }

    public function setEmail($value) {
        $this->set('email', $value);
    }

    public function getEmail() {
        return $this->get('email');
    }

    public function setActivate($value) {
        $this->set('activate', $value);
    }

    public function getActivate() {
        return $this->get('activate');
    }

    public function setAbout($value) {
        $this->set('about', $value);
    }

    public function getAbout() {
        return $this->get('about');
    }

    public function setGroupId($value) {
        $this->set('group_id', $value);
    }

    public function getGroupId() {
        return $this->get('group_id');
    }

    public function setLocation($value) {
        $this->set('location', $value);
    }

    public function getLocation() {
        return $this->get('location');
    }

    public function setInterests($value) {
        $this->set('interests', $value);
    }

    public function getInterests() {
        return $this->get('interests');
    }

    public function setWebsite($value) {
        $this->set('website', $value);
    }

    public function getWebsite() {
        return $this->get('website');
    }

    public function setAdmin($value) {
        $this->set('admin', $value);
    }

    public function getAdmin() {
        return $this->get('admin');
    }

    public function setJoined($value) {
        $this->set('joined', $value);
    }

    public function getJoined() {
        return $this->get('joined');
    }

    public function setAvatar($value) {
        $this->set('avatar', $value);
    }

    public function getAvatar() {
        return $this->get('avatar');
    }

    public function setReferrer($value) {
        $this->set('referrer', $value);
    }

    public function getReferrer() {
        return $this->get('referrer');
    }
}