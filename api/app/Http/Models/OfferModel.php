<?php

namespace App\Http\Models;

class OfferModel {

    private $offerId;
    private $senderId;
    private $receiverId;
    private $studentId;
    private $senderType;
    private $tutorLectureId;
    private $offer;
    private $currency;
    private $status;

    public function __construct($parameters = null) {
        $this->setOffer($parameters[OFFER_ID]);
        $this->setSenderId($parameters[SENDER_ID]);
        $this->setReceiverId($parameters[RECEIVER_ID]);
        $this->setStudentId($parameters[STUDENT_ID]);
        $this->setSenderType($parameters[SENDER_TYPE]);
        $this->setTutorLectureId($parameters[TUTOR_LECTURE_ID]);
        $this->setOffer($parameters[OFFER]);
        $this->setCurrency($parameters[CURRENCY]);
        $this->setStatus($parameters[STATUS]);
    }

    public function get() {
        return array(
            OFFER_ID => $this->getOfferId(),
            SENDER_ID => $this->getSenderId(),
            RECEIVER_ID => $this->getReceiverId(),
            STUDENT_ID => $this->getStudentId(),
            SENDER_TYPE => $this->getSenderType(),
            TUTOR_LECTURE_ID => $this->getTutorLectureId(),
            OFFER => $this->getOffer(),
            CURRENCY => $this->getCurrency(),
            STATUS => $this->getStatus()
        );
    }

    public function getOfferId() {
        return $this->offerId;
    }


    public function setOfferId($offerId): void {
        $this->offerId = $offerId;
    }


    public function getSenderId() {
        return $this->senderId;
    }


    public function setSenderId($senderId): void {
        $this->senderId = $senderId;
    }


    public function getReceiverId() {
        return $this->receiverId;
    }


    public function setReceiverId($receiverId): void {
        $this->receiverId = $receiverId;
    }


    public function getStudentId() {
        return $this->studentId;
    }


    public function setStudentId($studentId): void {
        $this->studentId = $studentId;
    }


    public function getSenderType() {
        return $this->senderType;
    }


    public function setSenderType($senderType): void {
        $this->senderType = $senderType;
    }


    public function getTutorLectureId() {
        return $this->tutorLectureId;
    }


    public function setTutorLectureId($tutorLectureId): void {
        $this->tutorLectureId = $tutorLectureId;
    }


    public function getOffer() {
        return $this->offer;
    }


    public function setOffer($offer): void {
        $this->offer = $offer;
    }


    public function getCurrency() {
        return $this->currency;
    }


    public function setCurrency($currency): void {
        $this->currency = $currency;
    }


    public function getStatus() {
        return $this->status;
    }


    public function setStatus($status): void {
        $this->status = $status;
    }

}
