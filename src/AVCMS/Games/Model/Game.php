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
        $this->set('name', $value);
    }

    public function getName() {
        return $this->get('name');
    }

    public function setDescription($value) {
        $this->set('description', $value);
    }

    public function getDescription() {
        return $this->get('description');
    }

    public function setUrl($value) {
        $this->set('url', $value);
    }

    public function getUrl() {
        return $this->get('url');
    }

    public function setCategoryId($value) {
        $this->set('category_id', $value);
    }

    public function getCategoryId() {
        return $this->get('category_id');
    }

    public function setCategoryParent($value) {
        $this->set('category_parent', $value);
    }

    public function getCategoryParent() {
        return $this->get('category_parent');
    }

    public function setHits($value) {
        $this->set('hits', $value);
    }

    public function getHits() {
        return $this->get('hits');
    }

    public function setPublished($value) {
        $this->set('published', $value);
    }

    public function getPublished() {
        return $this->get('published');
    }

    public function setUserSubmit($value) {
        $this->set('user_submit', $value);
    }

    public function getUserSubmit() {
        return $this->get('user_submit');
    }

    public function setWidth($value) {
        $this->set('width', $value);
    }

    public function getWidth() {
        return $this->get('width');
    }

    public function setHeight($value) {
        $this->set('height', $value);
    }

    public function getHeight() {
        return $this->get('height');
    }

    public function setImage($value) {
        $this->set('image', $value);
    }

    public function getImage() {
        return $this->get('image');
    }

    public function setImport($value) {
        $this->set('import', $value);
    }

    public function getImport() {
        return $this->get('import');
    }

    public function setFiletype($value) {
        $this->set('filetype', $value);
    }

    public function getFiletype() {
        return $this->get('filetype');
    }

    public function setInstructions($value) {
        $this->set('instructions', $value);
    }

    public function getInstructions() {
        return $this->get('instructions');
    }

    public function setMochi($value) {
        $this->set('mochi', $value);
    }

    public function getMochi() {
        return $this->get('mochi');
    }

    public function setRating($value) {
        $this->set('rating', $value);
    }

    public function getRating() {
        return $this->get('rating');
    }

    public function setFeatured($value) {
        $this->set('featured', $value);
    }

    public function getFeatured() {
        return $this->get('featured');
    }

    public function setDateAdded($value) {
        $this->set('date_added', $value);
    }

    public function getDateAdded() {
        return $this->get('date_added');
    }

    public function setAdvertId($value) {
        $this->set('advert_id', $value);
    }

    public function getAdvertId() {
        return $this->get('advert_id');
    }

    public function setHighscores($value) {
        $this->set('highscores', $value);
    }

    public function getHighscores() {
        return $this->get('highscores');
    }

    public function setMochiId($value) {
        $this->set('mochi_id', $value);
    }

    public function getMochiId() {
        return $this->get('mochi_id');
    }

    public function setSeoUrl($value) {
        $this->set('seo_url', $value);
    }

    public function getSeoUrl() {
        return $this->get('seo_url');
    }

    public function setSubmitter($value) {
        $this->set('submitter', $value);
    }

    public function getSubmitter() {
        return $this->get('submitter');
    }

    public function setHtmlCode($value) {
        $this->set('html_code', $value);
    }

    public function getHtmlCode() {
        return $this->get('html_code');
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