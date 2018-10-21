<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuitabilitySchedule extends Model {

    protected $table = 'user_suitability_schedule';

    protected $fillable = [
        'userId', 'region', 'location', 'courseType', 'facility', 'dayHourTable'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
