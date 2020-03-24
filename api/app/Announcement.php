<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model {

    protected $table = DB_ANNOUNCEMENT_TABLE;
    protected $primaryKey = ANNOUNCEMENT_ID;

    protected $fillable = [
        ANNOUNCEMENT_ID, TUTORED_ID, STUDENT_ID, LECTURE_AREA, LECTURE_THEME, CITY, DISTRICT, MIN_PRICE, MAX_PRICE,
        CURRENCY, SEX, STATUS
    ];

    public function tutoredId() {
        return $this->belongsTo('App\User');
    }

    public function studentId() {
        return $this->belongsToMany('App\Student');
    }
}
