<?php

namespace App\Http\Models;

class UserProfileModel {

    private $id;
    private $picture;
    private $name;
    private $surname;
    private $boost;
    private $recommend;
    private $profession;
    private $sex;
    private $birthday;
    private $city;
    private $district;
    private $description;
    private $tutorLectures;
    private $tutorSuitableRegions;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setId($parameters[IDENTIFIER]);
            $this->setPicture($parameters[PICTURE]);
            $this->setName($parameters[NAME]);
            $this->setSurname($parameters[SURNAME]);
            $this->setProfession($parameters[PROFESSION]);
            $this->setSex($parameters[SEX]);
            $this->setBirthday($parameters[BIRTHDAY]);
            $this->setCity($parameters[CITY]);
            $this->setDistrict($parameters[DISTRICT]);
            $this->setDescription($parameters[DESCRIPTION]);
        }
    }

    public function get() {
        return array(
            IDENTIFIER => $this->getId(),
            PICTURE => $this->getPicture(),
            NAME => $this->getName(),
            SURNAME => $this->getSurname(),
            BOOST => $this->getBoost(),
            RECOMMEND => $this->getRecommend(),
            PROFESSION => $this->getProfession(),
            SEX => $this->getSex(),
            BIRTHDAY => $this->getBirthday(),
            CITY => $this->getCity(),
            DISTRICT => $this->getDistrict(),
            DESCRIPTION => $this->getDescription(),
            TUTOR_LECTURES => $this->getTutorLectures(),
            TUTOR_SUITABLE_REGIONS => $this->getTutorSuitableRegions()
        );
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
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

    public function getBoost() {
        return $this->boost;
    }

    public function setBoost($boost): void {
        $this->boost = $boost;
    }

    public function getRecommend() {
        return $this->recommend;
    }

    public function setRecommend($recommend): void {
        $this->recommend = $recommend;
    }

    public function getProfession() {
        return $this->profession;
    }

    public function setProfession($profession): void {
        $this->profession = $profession;
    }

    public function getSex() {
        return $this->sex;
    }

    public function setSex($sex): void {
        $this->sex = $sex;
    }

    public function getBirthday() {
        return $this->birthday;
    }

    public function setBirthday($birthday): void {
        $this->birthday = $birthday;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city): void {
        $this->city = $city;
    }

    public function getDistrict() {
        return $this->district;
    }

    public function setDistrict($district): void {
        $this->district = $district;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description): void {
        $this->description = $description;
    }


    public function getTutorLectures() {
        return $this->tutorLectures;
    }

    public function setTutorLectures($tutorLectures): void {
        $this->tutorLectures = $tutorLectures;
    }


    public function getTutorSuitableRegions() {
        return $this->tutorSuitableRegions;
    }

    public function setTutorSuitableRegions($tutorSuitableRegions): void {
        $this->tutorSuitableRegions = $tutorSuitableRegions;
    }

}
