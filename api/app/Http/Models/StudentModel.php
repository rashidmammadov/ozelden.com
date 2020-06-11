<?php

namespace App\Http\Models;

class StudentModel {

    private $studentId;
    private $type;
    private $parentId;
    private $picture;
    private $name;
    private $surname;
    private $birthday;
    private $sex;
    private $parent;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setStudentId($parameters[STUDENT_ID]);
            $this->setType($parameters[TYPE]);
            $this->setParentId($parameters[PARENT_ID]);
            $this->setPicture($parameters[PICTURE]);
            $this->setName($parameters[NAME]);
            $this->setSurname($parameters[SURNAME]);
            $this->setBirthday($parameters[BIRTHDAY]);
            $this->setSex($parameters[SEX]);
        }
    }

    public function get() {
        return array(
            STUDENT_ID => $this->getStudentId(),
            TYPE => $this->getType(),
            PARENT_ID => $this->getParentId(),
            PICTURE => $this->getPicture(),
            NAME => $this->getName(),
            SURNAME => $this->getSurname(),
            BIRTHDAY => $this->getBirthday(),
            SEX => $this->getSex(),
            PARENT => $this->getParent()
        );
    }

    public function getStudentId() {
        return $this->studentId;
    }

    public function setStudentId($studentId): void {
        $this->studentId = $studentId;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type): void {
        $this->type = $type;
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

    public function getBirthday() {
        return $this->birthday;
    }

    public function setBirthday($birthday): void {
        $this->birthday = $birthday;
    }

    public function getSex() {
        return $this->sex;
    }

    public function setSex($sex): void {
        $this->sex = $sex;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setParent($parent): void {
        $this->parent = $parent;
    }

}
