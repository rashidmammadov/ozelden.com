<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuitableRegion extends Model {

    protected $table = DB_SUITABLE_REGION_TABLE;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        TUTOR_ID, CITY, DISTRICT
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
