<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model {

    protected $table = DB_OFFER_TABLE;
    protected $primaryKey = OFFER_ID;

    protected $fillable = [
        OFFER_ID, SENDER_ID, RECEIVER_ID, STUDENT_ID, SENDER_TYPE, TUTOR_LECTURE_ID, OFFER, CURRENCY, STATUS
    ];

    public function sender() {
        return $this->belongsToMany('App\User');
    }

    public function receiver() {
        return $this->belongsToMany('App\User');
    }

    public function student() {
        return $this->belongsToMany('App\Student');
    }

    public function tutorLecture() {
        return $this->belongsToMany('App\TutorLecture');
    }
}
