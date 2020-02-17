<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuitableCourseType extends Model {

    protected $table = DB_SUITABLE_COURSE_TYPE_TABLE;
    protected $primaryKey = TUTOR_ID;
    public $incrementing = false;

    protected $fillable = [
        TUTOR_ID, INDIVIDUAL, GROUP, CLASS_
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
