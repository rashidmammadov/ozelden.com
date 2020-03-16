<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\Log;

class TutorLectureModel {

    private $tutorLectureId;
    private $tutorId;
    private $lectureArea;
    private $lectureTheme;
    private $experience;
    private $price;
    private $currency;
    private $average_try;
    private $price_pleasure;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setTutorLectureId($parameters[TUTOR_LECTURE_ID]);
            $this->setTutorId($parameters[TUTOR_ID]);
            $this->setLectureArea($parameters[LECTURE_AREA]);
            $this->setLectureTheme($parameters[LECTURE_THEME]);
            $this->setExperience($parameters[EXPERIENCE]);
            $this->setPrice($parameters[PRICE]);
            $this->setCurrency($parameters[CURRENCY]);
            $this->setAverageTry($parameters[AVERAGE_TRY]);
        }
    }

    public function get() {
        return array(
            TUTOR_LECTURE_ID => $this->getTutorLectureId(),
            TUTOR_ID => $this->getTutorId(),
            LECTURE_AREA => $this->getLectureArea(),
            LECTURE_THEME => $this->getLectureTheme(),
            EXPERIENCE => $this->getExperience(),
            PRICE => $this->getPrice(),
            CURRENCY => $this->getCurrency(),
            AVERAGE_TRY => $this->getAverageTry(),
            PRICE_PLEASURE => $this->getPricePleasure(),
        );
    }

    public function getTutorLectureId() {
        return $this->tutorLectureId;
    }

    public function setTutorLectureId($tutorLectureId): void {
        $this->tutorLectureId = $tutorLectureId;
    }

    public function getTutorId() {
        return $this->tutorId;
    }

    public function setTutorId($tutorId): void {
        $this->tutorId = $tutorId;
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

    public function getExperience() {
        return $this->experience;
    }

    public function setExperience($experience): void {
        $this->experience = $experience;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price): void {
        $this->price = $price;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function setCurrency($currency): void {
        $this->currency = $currency;
    }

    public function getAverageTry() {
        return $this->average_try;
    }

    public function setAverageTry($average_try): void {
        $this->average_try = $average_try;
    }

    public function getPricePleasure() {
        return $this->price_pleasure;
    }

    public function setPricePleasure(TutorLectureModel $tutorLecture): void {
        $pleasure = null;
        $privilege = $tutorLecture->getAverageTry() + ($tutorLecture->getExperience() * 5 + $tutorLecture->getExperience());
        if ($tutorLecture->getAverageTry()) {
            if ($tutorLecture->getPrice() >= $tutorLecture->getAverageTry()) {
                if ($tutorLecture->getPrice() <= $privilege) {
                    $pleasure = 'good';
                } else if ($tutorLecture->getPrice() <= ($privilege + 20)) {
                    $pleasure = 'normal';
                } else {
                    $pleasure = 'bad';
                }
            } else {
                if ($tutorLecture->getPrice() > ($tutorLecture->getAverageTry() - 20)) {
                    $pleasure = 'good';
                } else if ($tutorLecture->getPrice() > ($tutorLecture->getAverageTry() - 40)) {
                    $pleasure = 'normal';
                } else {
                    $pleasure = 'bad';
                }
            }
        }
        $this->price_pleasure = $pleasure;
    }

}
