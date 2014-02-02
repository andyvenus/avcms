<?php
/**
 * User: Andy
 * Date: 04/01/2014
 * Time: 15:51
 */

namespace AVCMS\Games\Model;

use AVCMS\Core\Model\Entity;
use AVCMS\Core\Validation\Validatable;
use AVCMS\Core\Validation\Validator;
use AVCMS\Core\Validation\Rules;

class Game extends Entity implements Validatable {
    public function setName($value) {
        $this->setData('name', $value);
    }

    public function getName() {
        return $this->data('name');
    }

    public function setDescription($value) {
        $this->setData('description', $value);
    }

    public function getDescription() {
        return $this->data('description');
    }

    public function setUrl($value) {
        $this->setData('url', $value);
    }

    public function getUrl() {
        return $this->data('url');
    }

    public function setCategoryId($value) {
        $this->setData('category_id', $value);
    }

    public function getCategoryId() {
        return $this->data('category_id');
    }

    public function setCategoryParent($value) {
        $this->setData('category_parent', $value);
    }

    public function getCategoryParent() {
        return $this->data('category_parent');
    }

    public function setHits($value) {
        $this->setData('hits', $value);
    }

    public function getHits() {
        return $this->data('hits');
    }

    public function setPublished($value) {
        $this->setData('published', $value);
    }

    public function getPublished() {
        return $this->data('published');
    }

    public function setUserSubmit($value) {
        $this->setData('user_submit', $value);
    }

    public function getUserSubmit() {
        return $this->data('user_submit');
    }

    public function setWidth($value) {
        $this->setData('width', $value);
    }

    public function getWidth() {
        return $this->data('width');
    }

    public function setHeight($value) {
        $this->setData('height', $value);
    }

    public function getHeight() {
        return $this->data('height');
    }

    public function setImage($value) {
        $this->setData('image', $value);
    }

    public function getImage() {
        return $this->data('image');
    }

    public function setImport($value) {
        $this->setData('import', $value);
    }

    public function getImport() {
        return $this->data('import');
    }

    public function setFiletype($value) {
        $this->setData('filetype', $value);
    }

    public function getFiletype() {
        return $this->data('filetype');
    }

    public function setInstructions($value) {
        $this->setData('instructions', $value);
    }

    public function getInstructions() {
        return $this->data('instructions');
    }

    public function setMochi($value) {
        $this->setData('mochi', $value);
    }

    public function getMochi() {
        return $this->data('mochi');
    }

    public function setRating($value) {
        $this->setData('rating', $value);
    }

    public function getRating() {
        return $this->data('rating');
    }

    public function setFeatured($value) {
        $this->setData('featured', $value);
    }

    public function getFeatured() {
        return $this->data('featured');
    }

    public function setDateAdded($value) {
        $this->setData('date_added', $value);
    }

    public function getDateAdded() {
        return $this->data('date_added');
    }

    public function setAdvertId($value) {
        $this->setData('advert_id', $value);
    }

    public function getAdvertId() {
        return $this->data('advert_id');
    }

    public function setHighscores($value) {
        $this->setData('highscores', $value);
    }

    public function getHighscores() {
        return $this->data('highscores');
    }

    public function setMochiId($value) {
        $this->setData('mochi_id', $value);
    }

    public function getMochiId() {
        return $this->data('mochi_id');
    }

    public function setSeoUrl($value) {
        $this->setData('seo_url', $value);
    }

    public function getSeoUrl() {
        return $this->data('seo_url');
    }

    public function setSubmitter($value) {
        $this->setData('submitter', $value);
    }

    public function getSubmitter() {
        return $this->data('submitter');
    }

    public function setHtmlCode($value) {
        $this->setData('html_code', $value);
    }

    public function getHtmlCode() {
        return $this->data('html_code');
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('name', new Rules\Length(15), 'Entity says min length is 15');

        $validator->addRule('name', new Rules\MustNotExist('AVCMS\Games\Model\Games', 'name', $this->getId()), 'That name already exists sonny boy');
    }

    public function getValidationData()
    {
        return $this->toArray();
    }
}