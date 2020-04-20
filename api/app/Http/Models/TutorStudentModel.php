<?php

namespace App\Http\Models;

class TutorStudentModel {

    private $tutorId;
    private $userId;
    private $studentId;
    private $offerId;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setTutorId($parameters[TUTOR_ID]);
            $this->setUserId($parameters[USER_ID]);
            $this->setStudentId($parameters[STUDENT_ID]);
            $this->setOfferId($parameters[OFFER_ID]);
        }
    }

    public function get() {
        return array(
            TUTOR_ID => $this->getTutorId(),
            USER_ID => $this->getUserId(),
            STUDENT_ID => $this->getStudentId(),
            OFFER_ID => $this->getOfferId()
        );
    }

    public function getTutorId() {
        return $this->tutorId;
    }

    public function setTutorId($tutorId): void {
        $this->tutorId = $tutorId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId): void {
        $this->userId = $userId;
    }

    public function getStudentId() {
        return $this->studentId;
    }

    public function setStudentId($studentId): void {
        $this->studentId = $studentId;
    }

    public function getOfferId() {
        return $this->offerId;
    }

    public function setOfferId($offerId): void {
        $this->offerId = $offerId;
    }

}
