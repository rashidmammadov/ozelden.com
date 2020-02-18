<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuitableLocation extends Model {

    protected $table = DB_SUITABLE_LOCATION_TABLE;
    protected $primaryKey = TUTOR_ID;
    public $incrementing = false;

    protected $fillable = [
        TUTOR_ID, STUDENT_HOME, TUTOR_HOME, ETUDE, COURSE, LIBRARY, OVER_INTERNET
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
