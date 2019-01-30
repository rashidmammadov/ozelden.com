<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuitabilitySchedule extends Model {

    protected $table = 'user_suitability_schedule';
    protected $primaryKey = USER_ID;

    protected $fillable = [
        USER_ID, REGION, LOCATION, COURSE_TYPE, FACILITY, DAY_HOUR_TABLE
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
