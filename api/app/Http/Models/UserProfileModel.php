<?php

namespace App\Http\Models;

class UserProfileModel {

    private $id;
    private $type;
    private $picture;
    private $name;
    private $surname;
    private $phone;
    private $email;
    private $boost;
    private $recommend;
    private $profession;
    private $sex;
    private $birthday;
    private $city;
    private $district;
    private $description;
    private $tutoredAnnouncements;
    private $tutorStatistics;
    private $tutorLectures;
    private $tutorSuitability;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setId($parameters[IDENTIFIER]);
            $this->setType($parameters[TYPE]);
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
            TYPE => $this->getType(),
            PICTURE => $this->getPicture(),
            NAME => $this->getName(),
            SURNAME => $this->getSurname(),
            PHONE => $this->getPhone(),
            EMAIL => $this->getEmail(),
            BOOST => $this->getBoost(),
            RECOMMEND => $this->getRecommend(),
            PROFESSION => $this->getProfession(),
            SEX => $this->getSex(),
            BIRTHDAY => $this->getBirthday(),
            CITY => $this->getCity(),
            DISTRICT => $this->getDistrict(),
            DESCRIPTION => $this->getDescription(),
            TUTORED_ANNOUNCEMENTS => $this->getTutoredAnnouncements(),
            TUTOR_STATISTICS => $this->getTutorStatistics(),
            TUTOR_LECTURES => $this->getTutorLectures(),
            TUTOR_SUITABILITY => $this->getTutorSuitability()
        );
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type): void {
        $this->type = $type;
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


    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone): void {
        $this->phone = $phone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email): void {
        $this->email = $email;
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

    public function getTutoredAnnouncements() {
        return $this->tutoredAnnouncements;
    }

    public function setTutoredAnnouncements($tutoredAnnouncements): void {
        $this->tutoredAnnouncements = $tutoredAnnouncements;
    }

    public function getTutorStatistics() {
        return $this->tutorStatistics;
    }

    public function setTutorStatistics($tutorStatistics): void {
        $this->tutorStatistics = $tutorStatistics;
    }

    public function getTutorLectures() {
        return $this->tutorLectures;
    }

    public function setTutorLectures($tutorLectures): void {
        $this->tutorLectures = $tutorLectures;
    }


    public function getTutorSuitability() {
        return $this->tutorSuitability;
    }

    public function setTutorSuitability($tutorSuitability): void {
        $this->tutorSuitability = $tutorSuitability;
    }

}
