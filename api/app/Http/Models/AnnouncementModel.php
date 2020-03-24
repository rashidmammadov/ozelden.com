<?php

namespace App\Http\Models;

class AnnouncementModel {

    private $announcementId;
    private $tutoredId;
    private $tutored;
    private $studentId;
    private $student;
    private $lectureArea;
    private $lectureTheme;
    private $city;
    private $district;
    private $minPrice;
    private $maxPrice;
    private $currency;
    private $sex;
    private $status;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setAnnouncementId($parameters[ANNOUNCEMENT_ID]);
            $this->setTutoredId($parameters[TUTORED_ID]);
            $this->setStudentId($parameters[STUDENT_ID]);
            $this->setLectureArea($parameters[LECTURE_AREA]);
            $this->setLectureTheme($parameters[LECTURE_THEME]);
            $this->setCity($parameters[CITY]);
            $this->setDistrict($parameters[DISTRICT]);
            $this->setMinPrice($parameters[MIN_PRICE]);
            $this->setMaxPrice($parameters[MAX_PRICE]);
            $this->setCurrency($parameters[CURRENCY]);
            $this->setSex($parameters[SEX]);
            $this->setStatus($parameters[STATUS]);
        }
    }

    public function get() {
        return array(
            ANNOUNCEMENT_ID => $this->getAnnouncementId(),
            TUTORED_ID => $this->getTutoredId(),
            TUTORED => $this->getTutored(),
            STUDENT_ID => $this->getStudentId(),
            STUDENT => $this->getStudent(),
            LECTURE_AREA => $this->getLectureArea(),
            LECTURE_THEME => $this->getLectureTheme(),
            CITY => $this->getCity(),
            DISTRICT => $this->getDistrict(),
            MIN_PRICE => $this->getMinPrice(),
            MAX_PRICE => $this->getMaxPrice(),
            CURRENCY => $this->getCurrency(),
            SEX => $this->getSex(),
            STATUS => $this->getStatus()
        );
    }

    public function getAnnouncementId() {
        return $this->announcementId;
    }

    public function setAnnouncementId($announcementId): void {
        $this->announcementId = $announcementId;
    }

    public function getTutoredId() {
        return $this->tutoredId;
    }

    public function setTutoredId($tutoredId): void {
        $this->tutoredId = $tutoredId;
    }

    public function getTutored() {
        return $this->tutored;
    }

    public function setTutored($tutored): void {
        $this->tutored = $tutored;
    }

    public function getStudentId() {
        return $this->studentId;
    }

    public function setStudentId($studentId): void {
        $this->studentId = $studentId;
    }

    public function getStudent() {
        return $this->student;
    }

    public function setStudent($student): void {
        $this->student = $student;
    }

    public function getLectureArea() {
        return $this->lectureArea;
    }

    public function setLectureArea($lectureArea): void {
        $this->lectureArea = $lectureArea;
    }

    public function getLectureTheme() {
        return $this->lectureTheme;
    }

    public function setLectureTheme($lectureTheme): void {
        $this->lectureTheme = $lectureTheme;
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

    public function getMinPrice() {
        return $this->minPrice;
    }

    public function setMinPrice($minPrice): void {
        $this->minPrice = $minPrice;
    }

    public function getMaxPrice() {
        return $this->maxPrice;
    }

    public function setMaxPrice($maxPrice): void {
        $this->maxPrice = $maxPrice;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function setCurrency($currency): void {
        $this->currency = $currency;
    }

    public function getSex() {
        return $this->sex;
    }

    public function setSex($sex): void {
        $this->sex = $sex;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status): void {
        $this->status = $status;
    }

}
