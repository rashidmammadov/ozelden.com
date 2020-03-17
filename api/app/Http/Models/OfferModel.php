<?php

namespace App\Http\Models;

class OfferModel {

    private $offerId;
    private $senderId;
    private $sender;
    private $receiverId;
    private $receiver;
    private $studentId;
    private $student;
    private $senderType;
    private $tutorLectureId;
    private $tutorLecture;
    private $offer;
    private $currency;
    private $status;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setOffer($parameters[OFFER_ID]);
            $this->setSenderId($parameters[SENDER_ID]);
//            $this->setSender($parameters[SENDER]);
            $this->setReceiverId($parameters[RECEIVER_ID]);
//            $this->setReceiver($parameters[RECEIVER]);
            $this->setStudentId($parameters[STUDENT_ID]);
//            $this->setStudent($parameters[STUDENT]);
            $this->setSenderType($parameters[SENDER_TYPE]);
            $this->setTutorLectureId($parameters[TUTOR_LECTURE_ID]);
//            $this->setTutorLecture($parameters[TUTOR_LECTURE]);
            $this->setOffer($parameters[OFFER]);
            $this->setCurrency($parameters[CURRENCY]);
            $this->setStatus($parameters[STATUS]);
        }
    }

    public function get() {
        return array(
            OFFER_ID => $this->getOfferId(),
            SENDER_ID => $this->getSenderId(),
            SENDER => $this->getSender(),
            RECEIVER_ID => $this->getReceiverId(),
            RECEIVER => $this->getReceiver(),
            STUDENT_ID => $this->getStudentId(),
            STUDENT => $this->getStudent(),
            SENDER_TYPE => $this->getSenderType(),
            TUTOR_LECTURE_ID => $this->getTutorLectureId(),
            TUTOR_LECTURE => $this->getTutorLecture(),
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

    public function getSender() {
        return $this->sender;
    }

    public function setSender($sender): void {
        $this->sender = $sender;
    }

    public function getReceiverId() {
        return $this->receiverId;
    }

    public function getReceiver() {
        return $this->receiver;
    }

    public function setReceiver($receiver): void {
        $this->receiver = $receiver;
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

    public function getStudent() {
        return $this->student;
    }

    public function setStudent($student): void {
        $this->student = $student;
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

    public function getTutorLecture() {
        return $this->tutorLecture;
    }

    public function setTutorLecture($tutorLecture): void {
        $this->tutorLecture = $tutorLecture;
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
