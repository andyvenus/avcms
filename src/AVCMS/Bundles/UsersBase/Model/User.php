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
        $this->setData('id', $value);
    }

    /**
     * @return null
     */
    public function getId() {
        return $this->data('id');
    }

    public function setUsername($value) {
        $this->setData('username', $value);
    }

    public function getUsername() {
        return $this->data('username');
    }

    public function setPassword($value) {
        $this->setData('password', $value);
    }

    public function getPassword() {
        return $this->data('password');
    }

    public function setEmail($value) {
        $this->setData('email', $value);
    }

    public function getEmail() {
        return $this->data('email');
    }

    public function setActivate($value) {
        $this->setData('activate', $value);
    }

    public function getActivate() {
        return $this->data('activate');
    }

    public function setAbout($value) {
        $this->setData('about', $value);
    }

    public function getAbout() {
        return $this->data('about');
    }

    public function setGroupId($value) {
        $this->setData('group_id', $value);
    }

    public function getGroupId() {
        return $this->data('group_id');
    }

    public function setLocation($value) {
        $this->setData('location', $value);
    }

    public function getLocation() {
        return $this->data('location');
    }

    public function setInterests($value) {
        $this->setData('interests', $value);
    }

    public function getInterests() {
        return $this->data('interests');
    }

    public function setWebsite($value) {
        $this->setData('website', $value);
    }

    public function getWebsite() {
        return $this->data('website');
    }

    public function setAdmin($value) {
        $this->setData('admin', $value);
    }

    public function getAdmin() {
        return $this->data('admin');
    }

    public function setJoined($value) {
        $this->setData('joined', $value);
    }

    public function getJoined() {
        return $this->data('joined');
    }

    public function setAvatar($value) {
        $this->setData('avatar', $value);
    }

    public function getAvatar() {
        return $this->data('avatar');
    }

    public function setReferrer($value) {
        $this->setData('referrer', $value);
    }

    public function getReferrer() {
        return $this->data('referrer');
    }
}