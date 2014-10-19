<?php
/**
 * User: Andy
 * Date: 09/02/2014
 * Time: 19:16
 */

namespace AVCMS\Bundles\Users\Model;

use AV\Model\Entity;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;


class User extends Entity implements UserInterface
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

    public function setEmailValidated($value) {
        $this->set('email_validated', $value);
    }

    public function getEmailValidated() {
        return $this->get('email_validated');
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

    public function setCoverImage($value) {
        $this->set('cover_image', $value);
    }

    public function getCoverImage() {
        return $this->get('cover_image');
    }

    public function setReferrer($value) {
        $this->set('referrer', $value);
    }

    public function getReferrer() {
        return $this->get('referrer');
    }

    public function setSlug($value) {
        $this->set('slug', $value);
    }

    public function getSlug() {
        return $this->get('slug');
    }

    public function getRoleList()
    {
        return $this->get('role_list');
    }

    public function getLastIp() {
        return $this->get('last_ip');
    }

    public function setLastIp($value) {
        $this->set('last_ip', $value);
    }

    public function setRoleList($roleList)
    {
        $this->set('role_list', $roleList);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        $rolesArray = explode(',', $this->get('role_list'));

        $roles = array();
        foreach ($rolesArray as $roleStr) {
            $roles[] = new Role($roleStr);
        }

        return $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return;
    }

    public function isLoggedIn()
    {
        return $this->getId();
    }
}