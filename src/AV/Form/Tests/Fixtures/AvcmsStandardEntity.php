<?php
/**
 * User: Andy
 * Date: 18/01/2014
 * Time: 16:24
 */

namespace AV\Form\Tests\Fixtures;

use AV\Model\Entity;

class AvcmsStandardEntity extends Entity {
    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->set('category', $category);
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->get('category');
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->set('description', $description);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->set('name', $name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->get('name');
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->set('password', $password);
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->get('password');
    }

    /**
     * @param int $published
     */
    public function setPublished($published)
    {
        $this->set('published', $published);
    }

    /**
     * @return int
     */
    public function getPublished()
    {
        return $this->get('published');
    }
}