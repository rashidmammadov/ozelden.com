<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TutorLecture extends Model {

    protected $table = DB_TUTOR_LECTURE_TABLE;
    protected $primaryKey = TUTOR_LECTURE_ID;

    protected $fillable = [
        TUTOR_LECTURE_ID, TUTOR_ID, LECTURE_AREA, LECTURE_THEME, EXPERIENCE, PRICE, CURRENCY
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
