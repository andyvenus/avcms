<?php
/**
 * User: Andy
 * Date: 18/01/2014
 * Time: 16:24
 */

namespace AVCMS\Core\Form\Tests\Fixtures;


use AVCMS\Core\Model\Entity;

class AvcmsStandardEntity extends Entity {
    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->setData('category', $category);
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->data('category');
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->setData('description', $description);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->data('description');
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->setData('name', $name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->data('name');
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->setData('password', $password);
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->data('password');
    }

    /**
     * @param int $published
     */
    public function setPublished($published)
    {
        $this->setData('published', $published);
    }

    /**
     * @return int
     */
    public function getPublished()
    {
        return $this->data('published');
    }
}