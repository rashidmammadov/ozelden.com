<?php

namespace App\Http\Models;

class SearchModel {

    private $id;
    private $name;
    private $surname;
    private $birthday;
    private $sex;
    private $picture;
    private $profession;
    private $description;
    private $average;
    private $regions;
    private $lectures;

    public function __construct($parameters = null) {
        $this->setId($parameters[IDENTIFIER]);
        $this->setName($parameters[NAME]);
        $this->setSurname($parameters[SURNAME]);
        $this->setBirthday($parameters[BIRTHDAY]);
        $this->setSex($parameters[SEX]);
        $this->setPicture($parameters[PICTURE]);
        $this->setProfession($parameters[PROFESSION]);
        $this->setDescription($parameters[DESCRIPTION]);
        $this->setAverage($parameters[AVERAGE]);
        $this->setRegions($parameters[REGIONS]);
        $this->setLectures($parameters[LECTURES]);
    }

    public function get() {
        return array(
            IDENTIFIER => $this->getId(),
            NAME => $this->getName(),
            SURNAME => $this->getSurname(),
            BIRTHDAY => $this->getBirthday(),
            SEX => $this->getSex(),
            PICTURE => $this->getPicture(),
            PROFESSION => $this->getProfession(),
            DESCRIPTION => $this->getDescription(),
            AVERAGE => $this->getAverage(),
            REGIONS => $this->getRegions(),
            LECTURES => $this->getLectures()
        );
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
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

    public function getPicture() {
        return $this->picture;
    }

    public function setPicture($picture): void {
        $this->picture = $picture;
    }

    public function getProfession() {
        return $this->profession;
    }

    public function setProfession($profession): void {
        $this->profession = $profession;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description): void {
        $this->description = $description;
    }

    public function getAverage() {
        return $this->average;
    }

    public function setAverage($average): void {
        $this->average = $average;
    }

    public function getRegions() {
        return $this->regions;
    }

    public function setRegions($regions): void {
        $this->regions = $regions;
    }

    public function getLectures() {
        return $this->lectures;
    }

    public function setLectures($lectures): void {
        $this->lectures = $lectures;
    }

}
