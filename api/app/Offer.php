<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model {

    protected $table = DB_OFFER_TABLE;
    protected $primaryKey = OFFER_ID;

    protected $fillable = [
        OFFER_ID, SENDER_ID, RECEIVER_ID, STUDENT_ID, SENDER_TYPE, TUTOR_LECTURE_ID, OFFER, CURRENCY, STATUS
    ];

    public function senderId() {
        return $this->belongsToMany('App\User');
    }

    public function receiverId() {
        return $this->belongsToMany('App\User');
    }

    public function studentId() {
        return $this->belongsToMany('App\Student');
    }

    public function tutorLectureId() {
        return $this->belongsToMany('App\TutorLecture');
    }
}
