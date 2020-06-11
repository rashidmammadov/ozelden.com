<?php

namespace App\Http\Models;

class ParentModel {

    private $parentId;
    private $picture;
    private $name;
    private $surname;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setParentId($parameters[PARENT_ID]);
            $this->setPicture($parameters[PICTURE]);
            $this->setName($parameters[NAME]);
            $this->setSurname($parameters[SURNAME]);
        }
    }

    public function get() {
        return array(
            PARENT_ID => $this->getParentId(),
            PICTURE => $this->getPicture(),
            NAME => $this->getName(),
            SURNAME => $this->getSurname()
        );
    }

    public function getParentId() {
        return $this->parentId;
    }

    public function setParentId($parentId): void {
        $this->parentId = $parentId;
    }

    public function getPicture() {
        return $this->picture;
    }

    public function setPicture($picture): void {
        $this->picture = $picture;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setSurname($surname): void {
        $this->surname = $surname;
    }
}
