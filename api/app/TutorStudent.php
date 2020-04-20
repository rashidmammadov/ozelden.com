<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TutorStudent extends Model {

    protected $table = DB_TUTOR_STUDENT_TABLE;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        TUTOR_ID, USER_ID, STUDENT_ID, OFFER_ID
    ];

}
